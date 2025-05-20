    const submitBtn = document.getElementById("uSubmit");
    const resetBtn = document.getElementById("uReset");

    submitBtn.addEventListener("click", function () {
        const name = document.getElementById("uName").value.trim();
        const email = document.getElementById("uEmail").value.trim();
        const password = document.getElementById("uPass").value;
        const confirmPassword = document.getElementById("uConPass").value;
        const dob = document.getElementById("uDOB").value;
        const country = document.getElementById("uCountry").value;
        const genderMale = document.getElementById("uMale").checked;
        const genderFemale = document.getElementById("uFemale").checked;
        const color = document.getElementById("uColor").value;
        const termsAccepted = document.getElementById("uCheckbox").checked;

        const errors = [];

        if (name === "") {
            errors.push("Name is required.");
        } else if (!/^[A-Za-z.\s]+$/.test(name)) {
            errors.push("Name can contain only letters, dots, and spaces.");
        }        

        if (email === "") {
            errors.push("Email is required.");
        } else if (!/^\S+@\S+\.\S+$/.test(email)) {
            errors.push("Invalid email format.");
        }

        if (password === "") {
            errors.push("Password is required.");
        } else if (
            password.length < 8 ||
            !/[A-Za-z]/.test(password) || 
            !/[0-9]/.test(password)
            
        ) {
            errors.push("Password must be at least 8 characters, include mix of letters and digits.");
        }

        if (confirmPassword === "") {
            errors.push("Confirm Password is required.");
        } else if (confirmPassword !== password) {
            errors.push("Confirm Password does not match Password.");
        }

        if (dob === "") {
            errors.push("Date of Birth is required.");
        } else {
            const birthDate = new Date(dob);
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            const dayDiff = today.getDate() - birthDate.getDate();
        
            if (
                age < 18 ||
                (age === 18 && (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)))
            ) {
                errors.push("You must be at least 18 years old.");
            }
        }        

        if (!country) {
            errors.push("Country is required.");
        }

        if (!genderMale && !genderFemale) {
            errors.push("Gender is required.");
        }

        if (!color) {
            errors.push("Color is required.");
        }

        if (!termsAccepted) {
            errors.push("You must accept Terms & Conditions.");
        }
     
        if (errors.length > 0) {
            alert(errors.join("\n"));
        } 
        else {
            alert("Registration successful!");
            document.getElementById("regForm").submit();  
        }        
    });

    
    resetBtn.addEventListener("click", function () {
    document.querySelector("#regForm").reset();
    });