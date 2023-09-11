import { Chart } from 'chart.js/auto';
import 'chartjs-adapter-date-fns';

/** 
 * Event listener for form submission. This prevents the default form submission behavior 
 * and creates a URLSearchParams object from the form data. The page is then redirected to 
 * the updated URL with the form data as query parameters. This is useful for preserving 
 * form data across page loads.
 */
window.addEventListener('load', () => {
    // Event listener of submit of filter
    const form = document.querySelector('form');
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        const params = new URLSearchParams();
        for (const [key, value] of formData) {
            if (value !== '') {
                params.append(encodeURIComponent(key), encodeURIComponent(value));
            }
        }
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    });

    /** 
 * Event listener for delete buttons. When a delete button is clicked, the user is prompted 
 * for confirmation. If the user confirms, the module is deleted. If the user cancels, the 
 * deletion is prevented. This ensures that modules are not accidentally deleted.
 */
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            const moduleName = btn.getAttribute('data-module-name');
            const confirmation = window.confirm(`Voulez-vous vraiment supprimer le module ${moduleName}?`);
            if (!confirmation) {
                e.preventDefault();
            }
        });
    });

    /** 
 * Event listeners for article elements and their child elements. When an article element 
 * is clicked, data is fetched and a chart is generated based on the module ID. Child elements 
 * of the article element also have event listeners that stop the event from bubbling up and 
 * fetch data to generate a chart based on the parent article element's ID. This allows for 
 * interactive data visualization within each module.
 */

    // Get the article element
    const articleElements = document.querySelectorAll('article');
    let childElements = null;
    // Add event listener to the article element
    articleElements.forEach(function (articleElement) {
        articleElement.addEventListener('click', (event) => {
            const moduleId = event.currentTarget.id;
            fetchDataAndGenerateChart(moduleId);
        });
        childElements = articleElement.querySelectorAll('*');


        // Get all child elements of the article element


        // Add event listener to each child element
        childElements.forEach((childElement) => {
            childElement.addEventListener('click', (event) => {
                // Stop the event from bubbling up to the parent elements
                event.stopPropagation();

                // Get the id of the article element
                const moduleId = articleElement.id;
                fetchDataAndGenerateChart(moduleId);
            });
        });
    });
});

/** 
 * Function to fetch data and generate a chart. This function sends a POST request to an API 
 * endpoint with the module ID as a parameter. If the response is successful, the data is 
 * processed and passed to the handleGraphs function for chart generation. If there is an 
 * error, it is logged to the console. This function is responsible for retrieving the 
 * necessary data for chart generation.
 */
async function fetchDataAndGenerateChart(moduleId) {
    console.log(moduleId)
    try {
        const response = await fetch('/api/value-log/module', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                "moduleId": encodeURIComponent(moduleId)
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        handleGraphs(moduleId, data);

    } catch (error) {
        console.error('Error:', error);
    }
}


/** 
 * Function to handle the generation of graphs. This function clears existing graph elements, 
 * creates new div and canvas elements for each data entry, and appends them to the appropriate 
 * parent element. It then initializes a Chart.js chart with the fetched data. The graph 
 * element's max height is set to its scroll height to ensure it is fully visible. This function 
 * is responsible for the actual creation and display of the data charts.
 */
function handleGraphs(moduleId, datas) {
    const graphElements = document.querySelectorAll('[id^="graph-"]');
    graphElements.forEach(element => {
        element.classList.add('graph');
        while (element.firstChild) {
            element.firstChild.remove();
        }
    });
    for (const data of Object.entries(datas)) {
        const value_logs = data[1]["valueLog"];
        const valueTypeName = data[0];
        const unit = data[1]["unit"];
        // Create a new div with class 'col-12'
        const div = document.createElement('div');
        div.className = 'col-12 border border-3';
        div.style = 'border-color : #d8d8d8'



        // Create a new canvas element and append it to the div
        const canvas = document.createElement('canvas');
        canvas.id = `graph-${valueTypeName}`;
        canvas.className = 'm-3';
        canvas.style = "background-color : #d8d8d8"

        div.appendChild(canvas);

        // Append the div to the body (or wherever you want to place it)
        document.getElementById("graph-" + moduleId)
            .appendChild(div);

        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: Object.keys(value_logs),
                datasets: [{
                    label: valueTypeName + " : " + unit,
                    data: Object.values(value_logs),
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day'
                        }
                    }
                }
            }
        });
    }
    const graphElement = document.getElementById("graph-" + moduleId);
    graphElement.style.maxHeight = graphElement.scrollHeight + "px";

}