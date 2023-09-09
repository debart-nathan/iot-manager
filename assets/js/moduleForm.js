const fetchModuleTypeFullDescription = async (selectedModuleType) => {
    const response = await fetch('/api/get/module-type/full-description', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            moduleTypeName: selectedModuleType,
        }),
    });

    return response.json();
};

const clearElement = (element) => {
    while (element.firstChild) {
        element.firstChild.remove();
    }
};

const createDescription = (descriptionText) => {
    const description = document.createElement('p');
    description.textContent = descriptionText;
    return description;
};

const createValueTypesContainer = (valueTypes) => {
    const container = document.createElement('div');
    const header = document.createElement('h3');
    header.textContent = 'Value Types';
    container.appendChild(header);

    const list = document.createElement('ul');
    valueTypes.forEach((valueType) => {
        const listItem = document.createElement('li');
        listItem.textContent = `${valueType.name} (${valueType.unit})`;
        list.appendChild(listItem);
    });

    container.appendChild(list);
    return container;
};

const handleModuleTypeChange = async (event) => {
    const selectedModuleType = event.target.value;
    const data = await fetchModuleTypeFullDescription(selectedModuleType);

    const descriptionField = document.querySelector('#description-field');
    clearElement(descriptionField);

    const description = createDescription(data.description);
    descriptionField.appendChild(description);

    const valueTypesContainer = createValueTypesContainer(data.valueTypes);
    descriptionField.appendChild(valueTypesContainer);
};

document.addEventListener('DOMContentLoaded', () => {
    let module_type_name_select = document.querySelector('#module_type_name');
    if (module_type_name_select) {
        module_type_name_select.addEventListener('change', handleModuleTypeChange
            .catch(er => console.error("An Error occurred: ", error))
        );
    }
});