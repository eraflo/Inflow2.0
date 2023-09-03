import CKEDITORCustomAddOns from './modules/CKEDITORCustomAddOns.js';
import customMultipleFormDisplayHandler from './modules/customMultipleFormDisplayHandler.js';
let opinionManager = require('./modules/opinionAdder.js');
let tagsAndMentionsHandler = require('./modules/tagsAndMentionsHandler.js');

document.addEventListener('DOMContentLoaded', () => {

    let articleId = document.querySelector('.articleShow')?.getAttribute('data-article-id');

    if (articleId) {
        let articleOpinionAdderForm = document.querySelector('.articleShow .opinions form');
        opinionManager.addOpinion('/articles/' + articleId + '/opinions/add', articleOpinionAdderForm);
        // already done inside the template
        /* let articleContent = document.querySelector('.articleContent p');
        let newContent = tagsAndMentionsHandler.addTagLinks(articleContent?.innerHTML);
        articleContent ? articleContent.innerHTML = newContent : articleContent; */
    } else {
        let multipleChoicesForm = document.querySelector('#create_article_form_includes');
        let singleChoiceForm = document.querySelector('#create_article_form_categories');
        // the div that will contains the selected categories display ("addedChoicesDisplays")
        let addedChoicesDislpayContainer = document.querySelector('#addedCategoriesContainer');
        // the button that sends the singleChoiceForm
        let choiceAdder = document.querySelector('button#categoryAdder');

        let articleAdderFormAgrs = [multipleChoicesForm, singleChoiceForm, choiceAdder, addedChoicesDislpayContainer]
        if (articleAdderFormAgrs.every(arg => arg !== null)) {
            let customMultipleFormDisplayHandlerObj = new customMultipleFormDisplayHandler(...articleAdderFormAgrs);
            customMultipleFormDisplayHandlerObj.handle();
            CKEDITORCustomAddOns.test();
        }
    }

});