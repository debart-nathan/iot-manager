import { Chart } from 'chart.js/auto';
import 'chartjs-adapter-date-fns';

// Existing form submission code
window.addEventListener('load', () => {
    // Event listener of submit of filter
    const form = document.querySelector('form');
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const formData = new FormData(form);
        const params = new URLSearchParams();
        for (const [key, value] of formData) {
            if (value !== '') {
                params.append(key, value);
            }
        }
        window.location.href = `${window.location.pathname}?${params.toString()}`;
    });

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

// Function to fetch data and generate chart
async function fetchDataAndGenerateChart(moduleId) {
    console.log(moduleId)
    try {
        const response = await fetch('/api/value-log/module', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                "moduleId": moduleId
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