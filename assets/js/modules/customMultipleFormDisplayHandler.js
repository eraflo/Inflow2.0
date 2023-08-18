// "singleChoiceForm" correspond to the select/choicetype form without the multiple property,
// the idea is to get its value when sent and adding it to the form that has the multiple property ("multipleChoicesForm")

class customMultipleFormDisplayHandler {
    constructor(
        multipleChoicesForm,
        singleChoiceForm,
        choiceAdder,
        addedChoicesDislpayContainer
    ) {
        this.multipleChoicesForm = multipleChoicesForm;
        this.singleChoiceForm = singleChoiceForm;
        this.choiceAdder = choiceAdder;
        this.addedChoicesDislpayContainer = addedChoicesDislpayContainer;
    }

    handle() {
        // all the categories that the article already has (when editing it)
        let addedChoicesDisplays = addedChoicesDislpayContainer.querySelectorAll('.addedCategoryDisplay');
        for (addedChoiceDisplay of addedChoicesDisplays) {
            //console.log(addedChoiceDisplay);
            let deleteCategoryButton = addedChoiceDisplay.querySelector('button');
            addDeleteCategoryEvent(deleteCategoryButton);
        }

        let multipleChoicesFormOptions = multipleChoicesForm.querySelectorAll('option');

        // handling the singleChoiceForm submission
        choiceAdder.addEventListener('click', (e) => {
            e.preventDefault();
            //console.log('singleChoiceForm.value: ' + singleChoiceForm.value);
            for (let i = 0; i < multipleChoicesFormOptions.length; i++) {
                if (multipleChoicesFormOptions[i].value === singleChoiceForm.value && multipleChoicesFormOptions[i].selected === false) {
                    multipleChoicesFormOptions[i].selected = true;
                    let addedCategoryDisplay = createAddedCategoryDisplay().cloneNode(true);
                    //console.log(addedCategoryDisplay);
                    addedCategoryDisplay.setAttribute('value', multipleChoicesFormOptions[i].value);
                    addDeleteCategoryEvent(addedCategoryDisplay.querySelector('button'));
                    addedCategoryDisplay.querySelector('p').textContent = singleChoiceForm.multipleChoicesFormOptions[singleChoiceForm.selectedIndex].textContent;
                    addedChoicesDislpayContainer.appendChild(addedCategoryDisplay);
                    break;
                }
            }
        });

    }

    addDeleteCategoryEvent(deleteCategoryButton) {
        deleteCategoryButton.addEventListener('click', (e) => {
            e.preventDefault();
            multipleChoicesFormOptions[e.target.parentNode.getAttribute('value') - 1].selected = false;
            //console.log(e.target.parentNode.getAttribute('value'));
            e.target.parentNode.remove();
        });
    }

    createAddedCategoryDisplay() {

        // creation of the selected categories display template (used when creating or if the edited comment doesn't have any category)
        let addedCategoryDisplay = document.createElement('div');
        addedCategoryDisplay.classList.add('addedCategoryDisplay')
        let addedCategoryDisplayNamep = document.createElement('p');
        let deleteCategoryButton = document.createElement('button');
        addedCategoryDisplay.appendChild(addedCategoryDisplayNamep);
        deleteCategoryButton.textContent = 'x';
        addedCategoryDisplay.appendChild(deleteCategoryButton);
        addedCategoryDisplay.setAttribute('value', 'default');
        //console.log(addedChoicesDisplays);
    
        return addedCategoryDisplay;
    }

}

export default customMultipleFormDisplayHandler;