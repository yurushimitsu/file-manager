function downloadFolder(url) {
    // Make a request to the download route using fetch (or axios)
    fetch(url)
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    if (data.error) {
                        // Show SweetAlert if folder is empty
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: data.error,
                        });
                    }
                });
            }

            // If the folder is not empty, the server will handle the file download.
            window.location.href = url; // Trigger the download directly using the URL
        })
        .catch(error => {
            console.error('Error downloading folder:', error);
        });
}

function editFolderName(directory) {
    // Find the file name display element
    const folderNameElement = document.getElementById('folder-name-' + directory);
    folderNameElement.classList.add('p-0.5');

    // Store the original file name to revert back if needed
    const originalFolderName = directory;

    // Create an input field to edit the base file name (without extension)
    const inputField = document.createElement('input');
    inputField.type = 'text';
    inputField.value = directory; // Set the base name as the default value
    inputField.classList.add('text-md', 'font-bold', 'border', 'border-gray-300', 'rounded-lg', 'p-2', 'w-full');

    // Replace the text with the input field
    folderNameElement.innerHTML = '';
    folderNameElement.appendChild(inputField);

    // Focus on the input field
    inputField.focus();
    
    // Create a save button for the changes
    const saveButton = document.createElement('button');
    saveButton.textContent = 'Save';
    saveButton.classList.add('mt-2', 'me-1', 'bg-blue-500', 'cursor-pointer', 'hover:bg-blue-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');

    saveButton.onclick = function() {
        const newFolderName = inputField.value;
        if (newFolderName && newFolderName !== originalFolderName) {
            updateFileName(originalFolderName, newFolderName);
        } else {
            folderNameElement.innerHTML = originalFolderName;
        }
    };

    // Create a cancel button to revert changes
    const cancelButton = document.createElement('button');
    cancelButton.textContent = 'Cancel';
    cancelButton.classList.add('mt-2', 'bg-red-500', 'cursor-pointer', 'hover:bg-red-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');

    cancelButton.onclick = function() {
        // Revert back to the original file name
        folderNameElement.innerHTML = originalFolderName;
    };

    // Append the save and cancel buttons
    folderNameElement.appendChild(saveButton);
    folderNameElement.appendChild(cancelButton);

    inputField.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            // Trigger the save button's onclick event when Enter is pressed
            saveButton.click();
        }
    });
}

function editFolderNameList(directory) {
    const folderNameElement = document.getElementById('folder-name-list-' + directory);
    folderNameElement.classList.add('p-0.5');
    const originalFolderName = folderNameElement.innerText.trim(); // Get the current file name

    // Create an input field for editing the file name (without extension)
    const inputField = document.createElement('input');
    inputField.type = 'text';
    inputField.value = directory; // Set the base name as the default value
    inputField.classList.add('text-md', 'font-bold', 'border', 'border-gray-300', 'rounded-lg', 'p-1', 'w-full');

    // Clear the current file name and append the input field
    folderNameElement.innerHTML = '';  // Clear content
    folderNameElement.appendChild(inputField);  // Add input field
    inputField.focus(); // Focus on the input field

    // Get the parent file card element (for positioning the buttons inside the file-card)
    const folderCard = folderNameElement.closest('.folder-card.list-view');
    folderCard.style.position = 'relative'; // Make sure file card is positioned relative

    // Create the Save button
    const saveButtonList = document.createElement('button');
    saveButtonList.textContent = 'Save';
    saveButtonList.classList.add('mt-2', 'me-1', 'bg-blue-500', 'cursor-pointer', 'hover:bg-blue-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');
    saveButtonList.style.position = 'absolute';
    saveButtonList.style.top = '10px'; // Adjust as needed
    saveButtonList.style.right = '160px'; // Adjust as needed

    // Save button functionality
    saveButtonList.onclick = function() {
        const newFolderName = inputField.value;
        if (newFolderName && newFolderName !== directory) {
            updateFileName(originalFolderName, newFolderName);
        } else {
            folderNameElement.innerHTML = originalFolderName;
            saveButtonList.remove();
            cancelButton.remove();
        }
    };

    // Create the Cancel button
    const cancelButton = document.createElement('button');
    cancelButton.textContent = 'Cancel';
    cancelButton.classList.add('mt-2', 'bg-red-500', 'cursor-pointer', 'hover:bg-red-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');
    cancelButton.style.position = 'absolute';
    cancelButton.style.top = '10px'; // Adjust as needed
    cancelButton.style.right = '85px'; // Adjust as needed

    // Cancel button functionality
    cancelButton.onclick = function() {
        // Revert to original file name if cancel is clicked
        folderNameElement.innerHTML = originalFolderName;
        // Hide the Save and Cancel buttons after canceling
        saveButtonList.remove();
        cancelButton.remove();
    };

    // Append the Save and Cancel buttons to the file card element
    folderCard.appendChild(saveButtonList);
    folderCard.appendChild(cancelButton);

    inputField.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            // Trigger the save button's onclick event when Enter is pressed
            saveButtonList.click();
        }
    });
}

function editFileName(fileName, index) {
    // Split the file name and extension
    const fileParts = fileName.split('.');
    const fileExtension = fileParts.length > 1 ? '.' + fileParts.pop() : ''; // Get the extension (if any)
    const baseFileName = fileParts.join('.'); // Get the base name without the extension

    // Find the file name display element
    const fileNameElement = document.getElementById('file-name-' + fileName);
    fileNameElement.classList.add('p-0.5');

    // Store the original file name to revert back if needed
    const originalFileName = fileName;

    // Create an input field to edit the base file name (without extension)
    const inputField = document.createElement('input');
    inputField.type = 'text';
    inputField.value = baseFileName; // Set the base name as the default value
    inputField.classList.add('text-md', 'font-bold', 'border', 'border-gray-300', 'rounded-lg', 'p-2', 'w-full');

    // Replace the text with the input field
    fileNameElement.innerHTML = '';
    fileNameElement.appendChild(inputField);

    // Focus on the input field
    inputField.focus();
    
    // Create a save button for the changes
    const saveButton = document.createElement('button');
    saveButton.textContent = 'Save';
    saveButton.classList.add('mt-2', 'me-1', 'bg-blue-500', 'cursor-pointer', 'hover:bg-blue-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');

    saveButton.onclick = function() {
        const newBaseFileName = inputField.value.trim();
        if (newBaseFileName && newBaseFileName !== baseFileName) {
            const newFileName = newBaseFileName + fileExtension;

            // Call backend to update the file name
            updateFileName(originalFileName, newFileName);
        } else {
            fileNameElement.innerHTML = originalFileName;
        }
    };

    // Create a cancel button to revert changes
    const cancelButton = document.createElement('button');
    cancelButton.textContent = 'Cancel';
    cancelButton.classList.add('mt-2', 'bg-red-500', 'cursor-pointer', 'hover:bg-red-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');

    cancelButton.onclick = function() {
        // Revert back to the original file name
        fileNameElement.innerHTML = originalFileName;
    };

    // Append the save and cancel buttons
    fileNameElement.appendChild(saveButton);
    fileNameElement.appendChild(cancelButton);

    inputField.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            // Trigger the save button's onclick event when Enter is pressed
            saveButton.click();
        }
    });
}

function editFileNameList(fileName, index) {
    // Split the file name and extension
    const fileParts = fileName.split('.');
    const fileExtension = fileParts.length > 1 ? '.' + fileParts.pop() : ''; // Get the extension (if any)
    const baseFileName = fileParts.join('.'); // Get the base name without the extension

    const fileNameElement = document.getElementById('file-name-list-' + fileName);
    fileNameElement.classList.add('p-0.5');
    const originalFileName = fileNameElement.innerText.trim(); // Get the current file name

    // Create an input field for editing the file name (without extension)
    const inputField = document.createElement('input');
    inputField.type = 'text';
    inputField.value = baseFileName; // Set the base name as the default value
    inputField.classList.add('text-md', 'font-bold', 'border', 'border-gray-300', 'rounded-lg', 'p-1', 'w-full');

    // Clear the current file name and append the input field
    fileNameElement.innerHTML = '';  // Clear content
    fileNameElement.appendChild(inputField);  // Add input field
    inputField.focus(); // Focus on the input field

    // Get the parent file card element (for positioning the buttons inside the file-card)
    const fileCard = fileNameElement.closest('.file-card.list-view');
    fileCard.style.position = 'relative'; // Make sure file card is positioned relative

    // Create the Save button
    const saveButtonList = document.createElement('button');
    saveButtonList.textContent = 'Save';
    saveButtonList.classList.add('mt-2', 'me-1', 'bg-blue-500', 'cursor-pointer', 'hover:bg-blue-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');
    saveButtonList.style.position = 'absolute';
    saveButtonList.style.top = '10px'; // Adjust as needed
    saveButtonList.style.right = '160px'; // Adjust as needed

    // Save button functionality
    saveButtonList.onclick = function() {
        const newBaseFileName = inputField.value.trim();
        if (newBaseFileName && newBaseFileName !== baseFileName) {
            const newFileName = newBaseFileName + fileExtension;

            // Call backend to update the file name
            updateFileName(originalFileName, newFileName);
        } else {
            fileNameElement.innerHTML = originalFileName;
            saveButtonList.remove();
            cancelButton.remove();
        }
    };

    // Create the Cancel button
    const cancelButton = document.createElement('button');
    cancelButton.textContent = 'Cancel';
    cancelButton.classList.add('mt-2', 'bg-red-500', 'cursor-pointer', 'hover:bg-red-600', 'text-white', 'text-sm', 'rounded-lg', 'px-4', 'py-1');
    cancelButton.style.position = 'absolute';
    cancelButton.style.top = '10px'; // Adjust as needed
    cancelButton.style.right = '85px'; // Adjust as needed

    // Cancel button functionality
    cancelButton.onclick = function() {
        // Revert to original file name if cancel is clicked
        fileNameElement.innerHTML = originalFileName;
        // Hide the Save and Cancel buttons after canceling
        saveButtonList.remove();
        cancelButton.remove();
    };

    // Append the Save and Cancel buttons to the file card element
    fileCard.appendChild(saveButtonList);
    fileCard.appendChild(cancelButton);

    inputField.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            // Trigger the save button's onclick event when Enter is pressed
            saveButtonList.click();
        }
    });
}

function updateFileName(oldFileName, newFileName) {
    const data = {
        old_file_name: oldFileName,
        new_file_name: newFileName,
    };
    
    // Make the AJAX request (using fetch)
    fetch(window.Laravel.routes.updateFileName, {
        method: 'POST', // or 'PUT' depending on your controller method
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.Laravel.csrfToken
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(responseData => {
        if (responseData.success) {
            // alert('File name updated successfully');
            // Optionally, update the UI here with the new file name
            Swal.fire({
                icon: 'success',
                title: 'Edit Complete',
                text: responseData.message,
                timer: 3000,
                showConfirmButton: false,
                willClose: () => { location.reload(); }
            });
        } else {
            // alert('Failed to update the file name: ' + (responseData.message || 'Unknown error'));
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: responseData.message,
                showConfirmButton: true
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the file name');
    });
}