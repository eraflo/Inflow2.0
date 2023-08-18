class CKEDITORCustomAddOns {

    constructor() {

    }

    CKEDITORFiller() {

        CKEDITOR.on('instanceReady', (e) => {

            let HiddenTextArea = document.querySelector('#create_article_form_content');
            let editor = CKEDITOR.instances.create_article_form_content;
            let htmlContent = HiddenTextArea.getAttribute('value');
            //console.log(Object.keys(CKEDITOR.instances)[0]);
            editor.setData(htmlContent);

            /* let CKEditorIframeContainer = document.querySelector('#cke_1_contents');
            let CKEditorIframe = document.querySelector('iframe');
            let CKEditorIframeDoc = CKEditorIframe.contentDocument || CKEditorIframe.contentWindow.document;
            let visibleTextArea = CKEditorIframeDoc.querySelector('body').querySelector('p');
            let HiddenTextArea = document.querySelector('#create_article_form_content');
            console.log(HiddenTextArea.getAttribute('value'));

            visibleTextArea.textContent = HiddenTextArea.getAttribute('value'); */

        });
        
    }

    static test() {
        console.log(document.querySelector('#create_article_form_content').getAttribute('value'));
    }
}

export default CKEDITORCustomAddOns;