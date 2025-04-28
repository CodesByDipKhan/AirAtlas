document.getElementById("uForm").addEventListener("submit", function (e) {
    e.preventDefault();
    console.log("Form submitted");

    let messages = [];

    const uName = document.getElementById("uName").value.trim();
    const uNameRegex = /^[A-Za-z0-9]{6,}$/;
    if (!uNameRegex.test(uName)) {
        messages.push("Username must be at least 6 characters long and contain only letters and digits.");
    }

    const uEmail = document.getElementById("uEmail").value.trim();
    if (!uEmail.endsWith("@aiub.edu")) {
        messages.push("Email must end with '@aiub.edu'.");
    }

    const uPass = document.getElementById("uPass").value;
    const passRegex = /^(?=.*[A-Z])(?=.*[\W_]).{8,}$/;
    if (!passRegex.test(uPass)) {
        messages.push("Password must be at least 8 characters long, with at least one uppercase letter and one special character.");
    }

    const dob = document.getElementById("uDOB").value;
    if (dob === "") {
        messages.push("Date of birth must be selected.");
    } else {
        const birthDate = new Date(dob);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        if (age < 18) {
            messages.push("You must be at least 18 years old.");
        }
    }

    const country = document.getElementById("uCountry").value;
    if (!country) {
        messages.push("Please select a country.");
    }

    const isMale = document.getElementById("uMale").checked;
    const isFemale = document.getElementById("uFemale").checked;
    if (!isMale && !isFemale) {
        messages.push("Please select a gender.");
    }

    const agreed = document.getElementById("uCheckbox").checked;
    if (!agreed) {
        messages.push("You must agree to the Terms & Conditions.");
    }

    if (messages.length > 0) {
        alert("Validation Failed:\n\n" + messages.join("\n"));
    } else {
        alert("Success! All inputs are valid.");
        document.getElementById("uForm").reset();
    }
});