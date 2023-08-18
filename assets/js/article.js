import CKEDITORCustomAddOns from './modules/CKEDITORCustomAddOns.js';
import customMultipleFormDisplayHandler from './modules/customMultipleFormDisplayHandler.js';
let opinionManager = require('./modules/opinionAdder.js');

document.addEventListener('DOMContentLoaded', () => {


    let articleOpinionAdderForm = document.querySelector('.articleShow .opinions form');
    let articleId = document.querySelector('.articleShow').getAttribute('data-article-id');


    let multipleChoicesForm = document.querySelector('#create_article_form_includes');
    let singleChoiceForm = document.querySelector('#create_article_form_categories');
    // the div that will contains the selected categories display ("addedChoicesDisplays")
    let addedChoicesDislpayContainer = document.querySelector('#addedCategoriesContainer');
    // the button that sends the singleChoiceForm
    let choiceAdder = document.querySelector('button#categoryAdder');

    let articleAdderFormAgrs = [multipleChoicesForm, singleChoiceForm, addedChoicesDislpayContainer, choiceAdder]
    if (articleAdderFormAgrs.every(arg => arg !== null)) {
        let customMultipleFormDisplayHandlerObj = new customMultipleFormDisplayHandler(...articleAdderFormAgrs);
        customMultipleFormDisplayHandlerObj.handle();
        CKEDITORCustomAddOns.test();
    }


    opinionManager.addOpinion('/articles/' + articleId + '/opinions/add', articleOpinionAdderForm);

});