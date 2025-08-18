class TeacherSettings {
    constructor() {
        this.groupContainer = document.getElementById("groupClassesContainer");
        this.addGroupBtn = document.getElementById("addGroupBtn");
        this.groupCounter = 1;

        this.init();
    }

    init() {
        // Initialize existing groups
        this.initializeExistingGroups();

        // Bind event listeners
        this.bindEvents();

        // Initialize form validation
        this.initFormValidation();
    }

    initializeExistingGroups() {
        const existingGroups = document.querySelectorAll(".group-container");
        existingGroups.forEach((group) => this.initGroup(group));
    }

    bindEvents() {
        // Add new group button
        if (this.addGroupBtn) {
            this.addGroupBtn.addEventListener("click", () =>
                this.addNewGroup()
            );
        }

        // Delete group buttons (event delegation)
        if (this.groupContainer) {
            this.groupContainer.addEventListener("click", (e) => {
                if (e.target.closest(".delete-group-btn")) {
                    this.deleteGroup(e.target.closest(".group-container"));
                }
            });
        }

        // Form submission
        const form = document.getElementById("settingsForm");
        if (form) {
            form.addEventListener("submit", (e) => this.handleFormSubmit(e));
        }
    }

    addNewGroup() {
        const templateGroup = document.querySelector(".group-container");
        if (!templateGroup) return;

        const newGroup = templateGroup.cloneNode(true);

        // Update group counter and form names
        this.updateGroupFormNames(newGroup, this.groupCounter);

        // Clear form values
        this.clearGroupForm(newGroup);

        // Add delete button
        this.addDeleteButton(newGroup);

        // Insert new group
        templateGroup.parentNode.insertBefore(
            newGroup,
            this.addGroupBtn.parentNode
        );

        // Initialize the new group
        this.initGroup(newGroup);

        // Animate in
        this.animateGroupIn(newGroup);

        this.groupCounter++;
    }

    updateGroupFormNames(group, index) {
        const inputs = group.querySelectorAll('[name*="groups["]');
        inputs.forEach((input) => {
            const name = input.getAttribute("name");
            const newName = name.replace(/groups\[\d+\]/, `groups[${index}]`);
            input.setAttribute("name", newName);
        });
    }

    clearGroupForm(group) {
        // Clear text inputs
        const textInputs = group.querySelectorAll(
            'input[type="text"], input[type="number"]'
        );
        textInputs.forEach((input) => {
            if (input.classList.contains("editable-heading")) {
                input.value = "New Group Class";
            } else {
                input.value = "";
            }
        });

        // Reset selects to first option
        const selects = group.querySelectorAll("select");
        selects.forEach((select) => (select.selectedIndex = 0));

        // Uncheck all day buttons
        const dayButtons = group.querySelectorAll(".day-btn");
        dayButtons.forEach((btn) => {
            btn.classList.remove("selected", "active");
            const checkbox = btn.querySelector('input[type="checkbox"]');
            if (checkbox) checkbox.checked = false;
        });
    }

    addDeleteButton(group) {
        const deleteBtn = document.createElement("button");
        deleteBtn.type = "button";
        deleteBtn.className =
            "btn btn-sm btn-danger position-absolute top-0 end-0 mt-2 me-5 delete-group-btn";
        deleteBtn.innerHTML = '<i class="bi bi-trash"></i>';
        deleteBtn.setAttribute("aria-label", "Delete group");

        group.insertBefore(deleteBtn, group.firstChild);
    }

    deleteGroup(group) {
        if (document.querySelectorAll(".group-container").length <= 1) {
            this.showAlert(
                "You must have at least one group class.",
                "warning"
            );
            return;
        }

        // Animate out
        group.classList.add("slide-out");

        // Remove after animation
        setTimeout(() => {
            group.remove();
        }, 300);
    }

    initGroup(groupElement) {
        const daysContainer = groupElement.querySelector(".days-container");
        const lessonsPerWeekSelect = groupElement.querySelector(
            '[name*="lessons_per_week"]'
        );

        if (!daysContainer || !lessonsPerWeekSelect) return;

        this.bindDayButtons(daysContainer, lessonsPerWeekSelect);
        this.bindLessonsPerWeekChange(daysContainer, lessonsPerWeekSelect);
    }

    bindDayButtons(daysContainer, lessonsPerWeekSelect) {
        const dayButtons = daysContainer.querySelectorAll(".day-btn");

        dayButtons.forEach((button) => {
            button.addEventListener("click", (e) => {
                e.preventDefault();

                const maxLessons = parseInt(lessonsPerWeekSelect.value);
                const selectedDays =
                    daysContainer.querySelectorAll(".day-btn.selected");
                const checkbox = button.querySelector('input[type="checkbox"]');

                if (
                    !button.classList.contains("selected") &&
                    selectedDays.length >= maxLessons
                ) {
                    this.showAlert(
                        `You can only select ${maxLessons} days per week.`,
                        "warning"
                    );
                    return;
                }

                button.classList.toggle("selected");
                if (checkbox) {
                    checkbox.checked = button.classList.contains("selected");
                }
            });
        });
    }

    bindLessonsPerWeekChange(daysContainer, lessonsPerWeekSelect) {
        lessonsPerWeekSelect.addEventListener("change", () => {
            const maxLessons = parseInt(lessonsPerWeekSelect.value);
            const selectedDays =
                daysContainer.querySelectorAll(".day-btn.selected");

            if (selectedDays.length > maxLessons) {
                // Remove excess selections
                for (let i = maxLessons; i < selectedDays.length; i++) {
                    selectedDays[i].classList.remove("selected");
                    const checkbox = selectedDays[i].querySelector(
                        'input[type="checkbox"]'
                    );
                    if (checkbox) checkbox.checked = false;
                }
            }
        });
    }

    animateGroupIn(group) {
        group.style.opacity = "0";
        group.style.transform = "translateY(-10px)";

        // Force reflow
        group.offsetHeight;

        group.style.transition = "opacity 0.3s ease, transform 0.3s ease";
        group.style.opacity = "1";
        group.style.transform = "translateY(0)";
    }

    initFormValidation() {
        const form = document.getElementById("settingsForm");
        if (!form) return;

        // Add custom validation for group classes
        const groupContainers = form.querySelectorAll(".group-container");
        groupContainers.forEach((container) => {
            this.addGroupValidation(container);
        });
    }

    addGroupValidation(container) {
        const requiredFields = container.querySelectorAll("[required]");

        requiredFields.forEach((field) => {
            field.addEventListener("blur", () => {
                this.validateField(field);
            });
        });
    }

    validateField(field) {
        if (!field.value.trim()) {
            field.classList.add("is-invalid");
            return false;
        }

        field.classList.remove("is-invalid");
        field.classList.add("is-valid");
        return true;
    }

    handleFormSubmit(e) {
        let isValid = true;

        // Validate all required fields
        const requiredFields = e.target.querySelectorAll("[required]");
        requiredFields.forEach((field) => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        // Validate that each group has at least one day selected
        const groupContainers = e.target.querySelectorAll(".group-container");
        groupContainers.forEach((container) => {
            const selectedDays =
                container.querySelectorAll(".day-btn.selected");
            if (selectedDays.length === 0) {
                this.showAlert(
                    "Each group must have at least one day selected.",
                    "danger"
                );
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            this.showAlert(
                "Please fill in all required fields and fix any errors.",
                "danger"
            );
        }
    }

    showAlert(message, type = "info") {
        // Create alert element
        const alert = document.createElement("div");
        alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alert.style.cssText =
            "top: 20px; right: 20px; z-index: 9999; min-width: 300px;";
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(alert);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    new TeacherSettings();
});
