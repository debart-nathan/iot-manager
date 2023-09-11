import { Chart } from 'chart.js/auto';
import 'chartjs-adapter-date-fns';

/** 
 * Handles form submission, updates URL with form data.
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
    * Confirms before deleting a module.
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
     * On article click, fetches data and generates a chart.
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
 * Generates graphs from fetched data.
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