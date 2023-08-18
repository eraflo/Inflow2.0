/* import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["collectionContainer"]

    static values = {
        index    : Number,
        prototype: String,
    }

    addCollectionElement(event)
    {
        const item = document.createElement('li');
        const categoriesInput = document.querySelector('#categories');
        item.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue);
        //item.querySelector('input').value = categoriesInput.value;
        this.collectionContainerTarget.appendChild(item);

        const categoryInput = document.querySelector('#create_article_form_includes_' + this.indexValue + '_name');
        if (categoryInput) {
            categoryInput.value = categoriesInput.value;
        }

        this.indexValue++;
    }
} */