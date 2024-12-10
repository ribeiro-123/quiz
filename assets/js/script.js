
function togglePasswordVisibility(fieldId) {
    var field = document.getElementById(fieldId);
    var type = field.type === "password" ? "text" : "password";
    field.type = type;
}
