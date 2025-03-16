document.addEventListener('DOMContentLoaded', function () {
    const uploadButton = document.querySelector('.upload-btn');
    const surveyNameInput = document.querySelector('#survey-name');
    const surveyFileInput = document.querySelector('#survey-file');
    const previousSurveysTable = document.querySelector('tbody');
    const form = document.querySelector('form');
    
    // Handle the Survey Form Validation and Submission
    uploadButton.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent the form from submitting immediately

        // Check if the survey name and file input are valid
        if (!surveyNameInput.value || !surveyFileInput.files.length) {
            alert("Please provide both a survey name and upload a survey file.");
            return;
        }

        // Simulate the form submission (For demonstration, let's just log the input)
        alert("Survey uploaded successfully!");

        // Simulate adding the new survey to the table (if it were real, it would be added to a database)
        addNewSurveyToTable(surveyNameInput.value, new Date().toLocaleDateString());

        // Clear the form
        form.reset();
    });

    // Add New Survey to the Table
    function addNewSurveyToTable(surveyName, date) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${surveyName}</td>
            <td>${date}</td>
            <td><button class="view-btn">View</button></td>
        `;

        // Add event listener to "View" button
        const viewButton = row.querySelector('.view-btn');
        viewButton.addEventListener('click', function () {
            alert(`Viewing survey: ${surveyName}`);
        });

        // Append the new row to the table body
        previousSurveysTable.appendChild(row);
    }

    // Handle File Preview (Optional feature)
    surveyFileInput.addEventListener('change', function () {
        const file = surveyFileInput.files[0];

        if (file) {
            const fileReader = new FileReader();

            fileReader.onload = function (e) {
                const fileContent = e.target.result;
                
                if (file.type.startsWith("image/")) {
                    const imgPreview = document.createElement('img');
                    imgPreview.src = fileContent;
                    imgPreview.style.maxWidth = "100px";
                    imgPreview.style.display = "block";
                    document.body.appendChild(imgPreview); // You can append this to a specific preview section instead
                } else if (file.type === "text/plain") {
                    console.log("File Content Preview:", fileContent);
                } else {
                    console.log("File uploaded: ", file.name);
                }
            };

            fileReader.readAsDataURL(file);
        }
    });
});
