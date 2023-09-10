import { Chart } from 'chart.js';

// Existing form submission code
window.addEventListener('load', () => {
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
});

// Function to fetch data and generate chart
async function fetchDataAndGenerateChart(moduleId) {
    try {
        const response = await fetch('/api/value-log/module', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                moduleId
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        handleGraphs(data);

    } catch (error) {
        console.error('Error:', error);
    }
}

// Event listener for click on article
document.querySelector('article').addEventListener('click', (event) => {
    const moduleId = event.target.id;
    fetchDataAndGenerateChart(moduleId);
});

function handleGraphs(data) {
    for (const [valueTypeName, { value_logs }] of Object.entries(data)) {
        // Create a new div with class 'col-12'
        const div = document.createElement('div');
        div.className = 'col-12';

        // Create a new canvas element and append it to the div
        const canvas = document.createElement('canvas');
        canvas.id = `graph-${valueTypeName}`;
        div.appendChild(canvas);

        // Append the div to the body (or wherever you want to place it)
        document.body.appendChild(div);

        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: value_logs.map(({ log_date }) => log_date),
                datasets: [{
                    label: valueTypeName,
                    data: value_logs.map(({ data }) => data),
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}