window.onload = function () {
    // Selecting module type dropdown
    const module_type_dropdown = document.getElementById('module_form_module_type_name');

    // Setting up an event listener for the module type dropdown
    module_type_dropdown.addEventListener('change', function (e) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        populateDescription(selectedOption);
    });

    // Defining the populateDescription function
    function populateDescription(selectedOption) {
        console.log(selectedOption.value)
        fetch('/api/module-type/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ moduleTypeName: selectedOption.value }),
        })
            .then(response => response.json())
            .then(data => {
                console.log(data)

                let description = data.description;
                let picture_file = data.picture;

                // Update the description and image based on the fetched data
                document.getElementById('module-type-description').innerHTML = description;
                let imageElement = document.getElementById('module-type-picture');
                console.log(picture_file);
                imageElement.src = imageElement.dataset.src.replace("default.png", picture_file);
                imageElement.alt = selectedOption.value + ' picture';
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    };




    // Populating the description by default for the first option in the dropdown
    populateDescription(module_type_dropdown.options[0]);
}