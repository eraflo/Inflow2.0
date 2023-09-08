export const addOpinion = (url, form, commentId = 0, articleId = 0) => {

    url = url.replaceAll('{comment_id}', commentId);
    url = url.replaceAll('{article_id}', articleId);

    form.addEventListener('submit', (e) => {

        e.preventDefault();
        //console.log(Number(e.submitter.getAttribute('data-opinion')));

        //preparing the fetch request body

        const data = new URLSearchParams();
        let opinionValue = Number(e.submitter.getAttribute('data-opinion'));
        data.append('opinion', opinionValue);
        // console.log(data.toString());
        /* for (const pair of data.entries()) {
            console.log(pair[0], pair[1]);
        } */
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: data
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);

                //Displaying the opinion added (if it succeeded)
                updateOpinionDisplay(data.update_display, e.submitter);
            })
            .catch(error => {
                console.error(error);
            });
    });
};

function updateOpinionDisplay(status, submitter) {
    switch (status) {
        case 'add':
            submitter.value++;
            break;
        case 'delete':
            submitter.value--;
            break;
        case 'reverse':
            submitter.value++;
            //console.log(submitter);
            //console.log(submitter.parentNode.nextElementSibling);
            //console.log(submitter.parentNode.previousElementSibling);
            let siblingElement = (submitter.parentNode.nextElementSibling?.children[0]) || (submitter.parentNode.previousElementSibling?.children[0]);
            //console.log(siblingElement);
            siblingElement.value--;
            break;
        default:
            console.log('invalid opinion status')
            break;
    }
}