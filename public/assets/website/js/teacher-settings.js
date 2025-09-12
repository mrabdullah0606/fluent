class TeacherSettings {
    constructor() {
        this.groupContainer = document.getElementById("groupClassesContainer");
        this.addGroupBtn = document.getElementById("addGroupBtn");

        this.packageDiscounts = {
            1: 0.05,
            2: 0.1,
            3: 0.15,
        };

        this.init();
    }

    init() {
        this.initializeExistingGroups();
        this.bindEvents();
        this.initFormValidation();
        this.initPackagePricing();
    }

    // Get current group count dynamically
    getCurrentGroupCount() {
        return document.querySelectorAll(".group-container").length;
    }

    // Re-index all groups to ensure sequential numbering
    reindexAllGroups() {
        const groups = document.querySelectorAll(".group-container");
        groups.forEach((group, index) => {
            this.updateGroupFormNames(group, index);
        });
        console.log(`Re-indexed ${groups.length} groups`);
    }

    /* ---------------- PACKAGE PRICING ---------------- */
    initPackagePricing() {
        const durationInput = document.querySelector("#duration_60");
        if (!durationInput) return;

        durationInput.addEventListener("input", () =>
            this.updatePackagePrices()
        );

        document
            .querySelectorAll("[id^=package_][id$=_lessons]")
            .forEach((input) => {
                input.addEventListener("input", () =>
                    this.updatePackagePrices()
                );
            });

        this.updatePackagePrices();
    }

    updatePackagePrices() {
        const durationInput = document.querySelector("#duration_60");
        if (!durationInput) return;

        const basePrice = parseFloat(durationInput.value) || 0;

        Object.keys(this.packageDiscounts).forEach((i) => {
            const lessonsInput = document.querySelector(
                `#package_${i}_lessons`
            );
            const priceInput = document.querySelector(`#package_${i}_price`);

            if (lessonsInput && priceInput) {
                const lessons = parseInt(lessonsInput.value) || 0;
                let total = basePrice * lessons;
                let discount = this.packageDiscounts[i] || 0;
                let finalPrice = total - total * discount;
                priceInput.value = finalPrice.toFixed(2);
            }
        });
    }

    initializeExistingGroups() {
        const existingGroups = document.querySelectorAll(".group-container");
        existingGroups.forEach((group) => this.initGroup(group));
        // Ensure proper indexing from the start
        this.reindexAllGroups();
    }

    bindEvents() {
        if (this.addGroupBtn) {
            this.addGroupBtn.addEventListener("click", () =>
                this.addNewGroup()
            );
        }

        if (this.groupContainer) {
            this.groupContainer.addEventListener("click", (e) => {
                if (e.target.closest(".delete-group-btn")) {
                    this.deleteGroup(e.target.closest(".group-container"));
                }

                // Handle add schedule button
                if (e.target.closest(".add-schedule")) {
                    this.addScheduleItem(e.target.closest(".add-schedule"));
                }

                // Handle remove schedule button
                if (e.target.closest(".remove-schedule")) {
                    this.removeScheduleItem(
                        e.target.closest(".remove-schedule")
                    );
                }
            });
        }

        const form = document.getElementById("settingsForm");

        if (form) {
            form.addEventListener("submit", (e) => this.handleFormSubmit(e));
        }
    }

    addScheduleItem(button) {
        const groupIndex = button.getAttribute("data-group-index");
        const scheduleContainer = button.previousElementSibling;

        const scheduleItem = document.createElement("div");
        scheduleItem.className = "schedule-item row g-2 mb-2";
        scheduleItem.innerHTML = `
            <div class="col-6">
                <label class="form-label small text-muted">Date</label>
                <input type="date" name="groups[${groupIndex}][days][]" class="form-control" required>
            </div>
            <div class="col-6">
                <label class="form-label small text-muted">Time</label>
                <div class="input-group">
                    <input type="time" name="groups[${groupIndex}][times][]" class="form-control" required>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-schedule">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;

        scheduleContainer.appendChild(scheduleItem);
    }

    removeScheduleItem(button) {
        const scheduleItem = button.closest(".schedule-item");
        if (scheduleItem) {
            scheduleItem.remove();
        }
    }

    addNewGroup() {
        const templateGroup = document.querySelector(".group-container");
        if (!templateGroup) return;

        const newGroup = templateGroup.cloneNode(true);
        const newIndex = this.getCurrentGroupCount(); // Use current count as new index

        this.clearGroupForm(newGroup);
        this.updateGroupFormNames(newGroup, newIndex);
        this.addDeleteButton(newGroup);

        // Insert before the "Add New Group" button
        const addButtonContainer = this.addGroupBtn.parentNode;
        addButtonContainer.parentNode.insertBefore(
            newGroup,
            addButtonContainer
        );

        this.initGroup(newGroup);
        this.animateGroupIn(newGroup);

        console.log("New group added with index:", newIndex);
        console.log("Total groups now:", this.getCurrentGroupCount());
    }

    updateGroupFormNames(group, index) {
        const hiddenIdInput = group.querySelector(
            'input[type="hidden"][name*="[id]"]'
        );
        if (hiddenIdInput) {
            const name = hiddenIdInput.getAttribute("name");
            const newName = name.replace(/groups\[\d+\]/, `groups[${index}]`);
            hiddenIdInput.setAttribute("name", newName);
        }
        // Update all input names
        const inputs = group.querySelectorAll('[name*="groups["]');
        inputs.forEach((input) => {
            const name = input.getAttribute("name");
            const newName = name.replace(/groups\[\d+\]/, `groups[${index}]`);
            input.setAttribute("name", newName);
        });

        // Update element IDs
        const elementsWithIds = group.querySelectorAll('[id*="group_"]');
        elementsWithIds.forEach((element) => {
            const id = element.getAttribute("id");
            if (id) {
                const newId = id.replace(/group_\d+/, `group_${index}`);
                element.setAttribute("id", newId);
            }
        });

        // Update label for attributes
        const labels = group.querySelectorAll('label[for*="group_"]');
        labels.forEach((label) => {
            const forAttr = label.getAttribute("for");
            if (forAttr) {
                const newFor = forAttr.replace(/group_\d+/, `group_${index}`);
                label.setAttribute("for", newFor);
            }
        });

        // Update data-group-index for add-schedule button
        const addScheduleBtn = group.querySelector(".add-schedule");
        if (addScheduleBtn) {
            addScheduleBtn.setAttribute("data-group-index", index);
        }

        // Update schedule items
        const scheduleItems = group.querySelectorAll(".schedule-item");
        scheduleItems.forEach((item) => {
            const dateInputs = item.querySelectorAll('input[type="date"]');
            const timeInputs = item.querySelectorAll('input[type="time"]');

            dateInputs.forEach((input) => {
                const name = input.getAttribute("name");
                if (name) {
                    const newName = name.replace(
                        /groups\[\d+\]/,
                        `groups[${index}]`
                    );
                    input.setAttribute("name", newName);
                }
            });

            timeInputs.forEach((input) => {
                const name = input.getAttribute("name");
                if (name) {
                    const newName = name.replace(
                        /groups\[\d+\]/,
                        `groups[${index}]`
                    );
                    input.setAttribute("name", newName);
                }
            });
        });
    }

    clearGroupForm(group) {
        // Clear text inputs
        const textInputs = group.querySelectorAll(
            'input[type="text"], input[type="number"], input[type="date"], input[type="time"]'
        );
        textInputs.forEach((input) => {
            if (input.classList.contains("editable-heading")) {
                input.value = "New Group Class";
            } else {
                input.value = "";
            }
        });

        // Clear textareas
        const textareas = group.querySelectorAll("textarea");
        textareas.forEach((textarea) => {
            textarea.value = "";
        });

        // Reset selects to first option
        const selects = group.querySelectorAll("select");
        selects.forEach((select) => (select.selectedIndex = 0));

        // Clear all schedule items except the first one
        const scheduleContainer = group.querySelector(".schedule-container");
        if (scheduleContainer) {
            const scheduleItems =
                scheduleContainer.querySelectorAll(".schedule-item");
            scheduleItems.forEach((item, index) => {
                if (index > 0) {
                    item.remove();
                } else {
                    // Clear the first item's values
                    const dateInput = item.querySelector('input[type="date"]');
                    const timeInput = item.querySelector('input[type="time"]');
                    if (dateInput) dateInput.value = "";
                    if (timeInput) timeInput.value = "";
                }
            });
        }

        // Ensure the active checkbox is checked by default
        const activeCheckbox = group.querySelector('input[name*="is_active"]');
        if (activeCheckbox) {
            activeCheckbox.checked = true;
        }

        // Remove validation classes
        const validatedFields = group.querySelectorAll(
            ".is-valid, .is-invalid"
        );
        validatedFields.forEach((field) => {
            field.classList.remove("is-valid", "is-invalid");
        });
    }

    addDeleteButton(group) {
        // Remove existing delete button if any
        const existingDeleteBtn = group.querySelector(".delete-group-btn");
        if (existingDeleteBtn) {
            existingDeleteBtn.remove();
        }

        const deleteBtn = document.createElement("button");
        deleteBtn.type = "button";
        deleteBtn.className =
            "btn btn-sm btn-danger position-absolute top-0 end-0 mt-2 me-5 delete-group-btn";
        deleteBtn.innerHTML = '<i class="bi bi-trash"></i>';
        deleteBtn.setAttribute("aria-label", "Delete group");

        group.insertBefore(deleteBtn, group.firstChild);
    }

    deleteGroup(group) {
        const allGroups = document.querySelectorAll(".group-container");
        if (allGroups.length <= 1) {
            this.showAlert(
                "You must have at least one group class.",
                "warning"
            );
            return;
        }

        group.classList.add("slide-out");
        setTimeout(() => {
            group.remove();
            // Re-index all remaining groups after deletion
            this.reindexAllGroups();
            console.log("Group deleted, remaining groups re-indexed");
        }, 300);
    }

    initGroup(groupElement) {
        this.initDescriptionCounter(groupElement);
    }

    initDescriptionCounter(groupElement) {
        const textarea = groupElement.querySelector(
            'textarea[name*="description"]'
        );
        if (!textarea) return;

        // Check if counter already exists
        const existingCounter = textarea.parentNode.querySelector(
            ".char-count-container"
        );
        if (existingCounter) return;

        const counterDiv = document.createElement("div");
        counterDiv.className =
            "form-text text-muted small text-end mt-1 char-count-container";
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
                counter.style.color = "#dc3545";
            } else if (count > 400) {
                counter.style.color = "#fd7e14";
            } else {
                counter.style.color = "#6c757d";
            }
        };

        textarea.addEventListener("input", updateCounter);
        textarea.addEventListener("paste", () => setTimeout(updateCounter, 10));
        textarea.setAttribute("maxlength", "500");
        updateCounter();
    }

    animateGroupIn(group) {
        group.style.opacity = "0";
        group.style.transform = "translateY(-10px)";
        group.offsetHeight;
        group.style.transition = "opacity 0.3s ease, transform 0.3s ease";
        group.style.opacity = "1";
        group.style.transform = "translateY(0)";
    }

    initFormValidation() {
        const form = document.getElementById("settingsForm");
        if (!form) return;

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

    // FIXED: Remove debug code and actually submit the form
    handleFormSubmit(e) {
        console.log("Form submit handler triggered");

        // Re-index all groups before validation to ensure proper form names
        this.reindexAllGroups();

        let isValid = true;
        const form = e.target;

        // Get all group containers
        const groupContainers = form.querySelectorAll(".group-container");
        console.log(`Found ${groupContainers.length} groups to validate`);

        // Validate each group
        groupContainers.forEach((container, index) => {
            console.log(`Validating group ${index}`);

            // Check required fields
            const requiredFields = container.querySelectorAll("[required]");
            requiredFields.forEach((field) => {
                if (!this.validateField(field)) {
                    isValid = false;
                }
            });

            // Validate description fields
            const descriptionField = container.querySelector(
                'textarea[name*="description"]'
            );
            if (
                descriptionField &&
                !this.validateDescriptionField(descriptionField)
            ) {
                isValid = false;
            }

            // Validate that each group has at least one schedule
            const dateInputs = container.querySelectorAll('input[type="date"]');
            const hasValidSchedule = Array.from(dateInputs).some(
                (input) => input.value.trim() !== ""
            );

            if (!hasValidSchedule) {
                this.showAlert(
                    "Each group must have at least one scheduled day.",
                    "danger"
                );
                isValid = false;
            }
        });

        // Debug: Log form data before submission
        const formData = new FormData(form);
        console.log("Form data being submitted:");
        for (let [key, value] of formData.entries()) {
            if (key.includes("groups")) {
                console.log(`${key}: ${value}`);
            }
        }

        if (isValid) {
            console.log("✅ Form validation passed, submitting...");
            // REMOVED: e.preventDefault() - let the form submit naturally
            return true;
        } else {
            console.log("❌ Form validation failed");
            e.preventDefault();
            this.showAlert(
                "Please fill in all required fields and fix any errors.",
                "danger"
            );
            return false;
        }
    }

    showAlert(message, type = "info") {
        const alert = document.createElement("div");
        alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alert.style.cssText =
            "top: 20px; right: 20px; z-index: 9999; min-width: 300px;";
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(alert);

        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    new TeacherSettings();
});
