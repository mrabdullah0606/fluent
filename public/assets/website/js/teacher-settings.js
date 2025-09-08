class TeacherSettings {
    constructor() {
        this.groupContainer = document.getElementById("groupClassesContainer");
        this.addGroupBtn = document.getElementById("addGroupBtn");
        this.groupCounter = 1;

        // discount rules for packages
        this.packageDiscounts = {
            1: 0.05, // 5%
            2: 0.10, // 10%
            3: 0.15  // 15%
        };

        this.init();
    }

    init() {
        // Initialize existing groups
        this.initializeExistingGroups();

        // Bind event listeners
        this.bindEvents();

        // Initialize form validation
        this.initFormValidation();

        // Initialize lesson package pricing logic
        this.initPackagePricing();
    }

    /* ---------------- PACKAGE PRICING ---------------- */
    initPackagePricing() {
        const durationInput = document.querySelector("#duration_60");
        if (!durationInput) return;

        // Update on base price change
        durationInput.addEventListener("input", () => this.updatePackagePrices());

        // Update when number of lessons changes
        document.querySelectorAll("[id^=package_][id$=_lessons]").forEach(input => {
            input.addEventListener("input", () => this.updatePackagePrices());
        });

        // Run once at start
        this.updatePackagePrices();
    }

    updatePackagePrices() {
        const durationInput = document.querySelector("#duration_60");
        if (!durationInput) return;

        const basePrice = parseFloat(durationInput.value) || 0;

        Object.keys(this.packageDiscounts).forEach(i => {
            const lessonsInput = document.querySelector(`#package_${i}_lessons`);
            const priceInput = document.querySelector(`#package_${i}_price`);

            if (lessonsInput && priceInput) {
                const lessons = parseInt(lessonsInput.value) || 0;

                // Calculate total
                let total = basePrice * lessons;

                // Apply discount
                let discount = this.packageDiscounts[i] || 0;
                let finalPrice = total - (total * discount);

                // Update field
                priceInput.value = finalPrice.toFixed(2);
            }
        });
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

        // Update IDs for form elements
        const elementsWithIds = group.querySelectorAll('[id*="group_"]');
        elementsWithIds.forEach((element) => {
            const id = element.getAttribute("id");
            if (id) {
                const newId = id.replace(/group_\d+/, `group_${index}`);
                element.setAttribute("id", newId);
            }
        });

        // Update labels' for attributes
        const labels = group.querySelectorAll('label[for*="group_"]');
        labels.forEach((label) => {
            const forAttr = label.getAttribute("for");
            if (forAttr) {
                const newFor = forAttr.replace(/group_\d+/, `group_${index}`);
                label.setAttribute("for", newFor);
            }
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

        // Clear textarea (description)
        const textareas = group.querySelectorAll("textarea");
        textareas.forEach((textarea) => {
            textarea.value = "";
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
        this.initDescriptionCounter(groupElement);
    }

    initDescriptionCounter(groupElement) {
        const textarea = groupElement.querySelector(
            'textarea[name*="description"]'
        );
        if (!textarea) return;

        // Add character counter
        const counterDiv = document.createElement("div");
        counterDiv.className = "form-text text-muted small text-end mt-1";
        counterDiv.innerHTML =
            '<span class="char-count">0</span>/500 characters';

        const helpText = textarea.parentNode.querySelector(".form-text");
        if (helpText) {
            helpText.parentNode.insertBefore(counterDiv, helpText.nextSibling);
        }

        const updateCounter = () => {
            const count = textarea.value.length;
            const counter = counterDiv.querySelector(".char-count");
            counter.textContent = count;

            if (count > 450) {
                counter.style.color = "#dc3545"; // Red
            } else if (count > 400) {
                counter.style.color = "#fd7e14"; // Orange
            } else {
                counter.style.color = "#6c757d"; // Gray
            }
        };

        textarea.addEventListener("input", updateCounter);
        textarea.addEventListener("paste", () => setTimeout(updateCounter, 10));

        // Set max length
        textarea.setAttribute("maxlength", "500");

        // Initial count
        updateCounter();
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

        // Add validation for description length
        const descriptionField = container.querySelector(
            'textarea[name*="description"]'
        );
        if (descriptionField) {
            descriptionField.addEventListener("blur", () => {
                this.validateDescriptionField(descriptionField);
            });
        }
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

    validateDescriptionField(field) {
        const maxLength = 500;
        if (field.value.length > maxLength) {
            field.classList.add("is-invalid");
            this.showAlert(
                `Description must be ${maxLength} characters or less.`,
                "warning"
            );
            return false;
        }

        field.classList.remove("is-invalid");
        if (field.value.trim()) {
            field.classList.add("is-valid");
        }
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

        // Validate description fields
        const descriptionFields = e.target.querySelectorAll(
            'textarea[name*="description"]'
        );
        descriptionFields.forEach((field) => {
            if (!this.validateDescriptionField(field)) {
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
