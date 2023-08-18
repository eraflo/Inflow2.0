let opinionManager = require('./opinionAdder.js');

let articleId = document.querySelector('.articleShow').getAttribute('data-article-id');

export function addEditionClickEvent(commentEditionLink, commentForm, commentEditionUrl, userProfilePath, commentId = 0) {

    let cancelFormSubmissionButton = commentForm.querySelector('button.cancel');
    addOnCancel(cancelFormSubmissionButton);
    addOnSubmit(commentForm, commentEditionUrl);

    commentEditionUrl = commentEditionUrl.replaceAll('{comment_id}', commentId);

    commentEditionLink.addEventListener("click", (e) => {
        e.preventDefault();
        document.querySelector('form#commentEdition')?.remove();

        let comment = e.target.closest(".comment");

        //adding an event to cancel/revert the comment editing

        let commentContent = comment.querySelector(".comment-content");
        let commentContentTextarea = commentForm.querySelector('textarea');
        commentContentTextarea.value = commentContent.textContent.trim();
        comment.appendChild(commentForm);

        commentContent.style.display = 'none';
        comment.querySelector(".comment-edition-deletion").style.display = 'none';

        commentForm.addEventListener('commentSubmission', (e) => {
            commentContentTextarea.disabled = true;
        });

        commentForm.addEventListener('commentSubmissionSucceeded', (e) => {
            //console.log(e.detail);
            if (e.detail.type === 'edition') {
                commentContentTextarea.disabled = false;
                commentForm.remove();
                comment.querySelector('div.error')?.remove();

                let authorDiv = comment.querySelector('.author');
                userProfilePath = userProfilePath.replaceAll('{user_id}', e.detail.comment.author.id);
                let authorLink = document.createElement('a');
                authorLink.href = userProfilePath;
                authorLink.textContent = '@' + e.detail.comment.author.username;
                authorDiv.querySelector('a').remove();
                authorDiv.appendChild(authorLink);

                commentContent.style.display = 'flex';
                commentContent.innerHTML = e.detail.comment.content;
                comment.querySelector(".comment-edition-deletion").style.display = 'flex';
            }
        });

        document.addEventListener('commentSubmissionFailed', (e) => {
            if (e.detail.type === 'edition') {
                commentContentTextarea.disabled = false;
                if (comment.querySelector('div.error')) {
                    let errorDiv = document.createElement('div').classList.add('error');
                    errorDiv.textContent = "Une erreur est survenue."
                    comment.appendChild(errorDiv);
                }
            }
        });

    });

}

export function initializeComment(commentObject, commentTemplate, commentForm, mainThreadCommentId, commentAdderUrl, commentEditionUrl,  opinionAdderUrl, commentDeletionUrl, userProfilePath) {

    let comment = commentTemplate.cloneNode(true);

    commentForm = commentForm.cloneNode(true);
    commentForm.setAttribute('id', 'commentEdition');
    addEditionClickEvent(comment.querySelector('a.commentEdition'), commentForm, commentEditionUrl.replaceAll('{comment_id}', commentObject.id), userProfilePath);
    commentForm = commentForm.cloneNode(true);
    commentForm.setAttribute('id', 'commentReply');
    addReplyAdditionClickEvent(mainThreadCommentId, comment.querySelector('a.add-reply'), commentForm, commentAdderUrl, commentEditionUrl, opinionAdderUrl, commentDeletionUrl, userProfilePath, commentTemplate);
    let commentDeletionForm = comment.querySelector("form[name='commentDeletion']");
    addFormDeletionEvent(commentDeletionForm, commentDeletionUrl, commentObject.id);
    
    let opinionForm = comment.querySelector('.opinions form');
    opinionManager.addOpinion(opinionAdderUrl, opinionForm, commentObject.id);
    comment.setAttribute('data-comment-id', commentObject.id);
    comment.querySelector('div.comment-content').innerHTML = commentObject.content;
    userProfilePath = userProfilePath.replaceAll('{user_id}', commentObject.author.id);
    let authorLink = document.createElement('a');
    authorLink.href = userProfilePath;
    authorLink.textContent = '@' + commentObject.author.username;
    comment.querySelector('.author').appendChild(authorLink);

    return comment;
}

export function handleCommentAddition(commentForm, commentTemplate, commentAdderUrl, commentEditionUrl, opinionAdderUrl, commentDeletionUrl, userProfilePath, repliesTo = null) {

    addOnSubmit(commentForm, commentAdderUrl, repliesTo);

    let commentContentTextarea = commentForm.querySelector('textarea');
    let cancelFormSubmissionButton = commentForm.querySelector('button.cancel');

    if (commentForm.getAttribute('id') === 'commentAddition') {
        cancelFormSubmissionButton.addEventListener('click', (e) => {
            e.preventDefault();
            commentContentTextarea.value = "";
        });
    } else {
        addOnCancel(cancelFormSubmissionButton);
    }

    commentForm.addEventListener('commentSubmission', (e) => {
        commentContentTextarea.disabled = true;
    });

    commentForm.addEventListener('commentSubmissionSucceeded', (e) => {

        //not really useful since the commentForm in deleted in case of success
        commentForm.querySelector('div.error')?.remove();

        // emptying the textarea once the comment is added

        commentContentTextarea.disabled = false;
        commentContentTextarea.value = "";

        // initializing the comment to avoid errors in the switch case statement:
            //for the 'reply' and 'addition' cases, a new comment is created and returned by initializeComment(); (1)
            //for the 'edition' case, it is retrieved in the document thanks to its id being returned by the event object. (2)

        let comment;
        let repliesDiv;
        let commentsDiv = document.querySelector('div.comments');

        //console.log(e.detail);

        // (1)
        if (e.detail.type === 'reply' || e.detail.type === 'addition') {
            let mainThreadCommentId = e.detail.comment.repliesTo?.id /* reply case */ || e.detail.comment.id /* addition case */;
            comment = initializeComment(e.detail.comment, commentTemplate, commentForm, mainThreadCommentId, commentAdderUrl, commentEditionUrl, opinionAdderUrl, commentDeletionUrl, userProfilePath);
        }
        
        switch (e.detail.type) {
            case 'reply':
                comment.classList.add('reply');
                let repliesTo = commentsDiv.querySelector('.comment[data-comment-id="' + e.detail.comment.repliesTo.id + '"]');

                // the comment-with-replies div contains the comment div and the replies div

                repliesDiv = repliesTo.closest('.comment-with-replies').querySelector('.replies');
                //console.log(comment);
                repliesDiv.appendChild(comment);
                commentForm.remove();
                break;

            case 'addition':
                let commentWithRepliesDiv = document.createElement('div');
                commentWithRepliesDiv.classList.add('comment-with-replies');
                repliesDiv = document.createElement('div');
                repliesDiv.classList.add('replies');
                commentWithRepliesDiv.appendChild(comment);
                commentWithRepliesDiv.appendChild(repliesDiv);
                commentsDiv.appendChild(commentWithRepliesDiv);
                break;

            case 'edition':
                // (2)
                comment = commentsDiv.querySelector('.comment[data-comment-id="' + e.detail.comment.id + '"]');
                let commentContent = comment.querySelector('.comment-content');
                let commentEditionDeletion = comment.querySelector('.comment-edition-deletion');
                commentContent.innerHTML = e.detail.comment.content;
                commentContent.style.display = 'flex';
                commentEditionDeletion.style.display = 'flex';
                comment.querySelector('form#commentEdition')?.remove();
                break;
        
            default:
                console.log('unknown operation type: ' + e.detail.type + ', check the serialized object returned by your backend!');
                break;
        }

        //adding the comment added to the list of comments

        //document.querySelector('.comments').appendChild(commentTemplate);
    });

    commentForm.addEventListener('commentSubmissionFailed', (e) => {
        commentContentTextarea.disabled = false;
        if (!commentForm.querySelector('div.error')) {
            let errorDiv = document.createElement('div').classList.add('error');
            errorDiv.textContent = "Une erreur est survenue, réessayez."
            commentForm.querySelector('.commentSubmission').prepend(errorDiv);
        }
    });
}

let handleSubmit;

export function addOnSubmit(form, url, repliesTo/* (optional)*/) {
    form.addEventListener("submit", handleSubmit = function(e) {

        e.preventDefault();
        let formData = new FormData(form);
        if (repliesTo) {
            formData.set('comment[repliesTo]', repliesTo);
        }
        let commentSubmission = new CustomEvent('commentSubmission', {
            detail: {
                formData: formData
            }
        });
        form.dispatchEvent(commentSubmission);

        fetch(url, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then((data) => {
                //the serialization returns a string, then the string is json stringified, so we need to parse it twice
                if (typeof data === "string") {
                    data = JSON.parse(data);
                }
                
                console.log(data);

                let commentSubmissionSucceeded = new CustomEvent('commentSubmissionSucceeded', {
                    detail: {
                        comment: data.comment,
                        type: data.type
                    }
                });
                form.dispatchEvent(commentSubmissionSucceeded);
            })
            .catch((error) => {
                console.error(error);

                let commentSubmissionFailed = new CustomEvent("commentSubmissionFailed", {
                    detail: {
                        error: error
                    }
                });
                form.dispatchEvent(commentSubmissionFailed);
            });
    });
}

//export {handleSubmit};

export function addOnCancel(button) {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        let commentForm = e.target.closest("form");
        let comment = commentForm.closest(".comment");
        if (comment) {
            comment.querySelector(".comment-content").style.display = 'flex';
            comment.querySelector(".comment-edition-deletion").style.display = 'flex';
        }
        commentForm.remove();
    });
}


export function addFormDeletionEvent(commentDeletionForm, commentDeletionUrl, commentId = 0, articleId = 0) {

    commentDeletionForm.addEventListener("submit", (e) => {

        e.preventDefault();
        let comment = e.target.closest('.comment');
        let loadingChanges = document.createElement('div');
        loadingChanges.classList.add('loadingChanges');
        comment.appendChild(loadingChanges);
        //console.log(...commentDeletionFormData);
        commentDeletionUrl = commentDeletionUrl.replaceAll('{comment_id}', commentId);
        commentDeletionUrl = commentDeletionUrl.replaceAll('{article_id}', articleId);

        fetch(commentDeletionUrl, {
            method: "POST",
            body: new FormData(commentDeletionForm)
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                comment.removeChild(loadingChanges);
                if (comment.classList.contains('reply')) {
                    comment.remove();
                } else {
                    comment.closest('.comment-with-replies').remove();
                }
            })
            .catch(error => {
                console.error(error);
                comment.removeChild(loadingChanges);
            });
    });
}

export function addReplyAdditionClickEvent(mainThreadCommentId, addCommentReplyLink, commentForm, commentAdderUrl, commentEditionUrl, opinionAdderUrl, commentDeletionUrl, userProfilePath, commentTemplate) {

    addCommentReplyLink.addEventListener('click', (e) => {

        //reset all the eventListeners to avoid readding then in cycle
        commentForm = commentForm.cloneNode(true);
        document.querySelector('form#replyAddition')?.remove();
        commentForm.querySelector('textarea').value = addCommentReplyLink.closest('.comment').querySelector('.author a').textContent + " ";
        
        //let comment = e.target.closest('.comment');
        //let mainThreadComment = e.target.closest('.comment');

        let repliesTo = document.querySelector('.comment[data-comment-id="' + mainThreadCommentId + '"]');
        //console.log(repliesTo);
        let repliesDiv = repliesTo.closest('.comment-with-replies').querySelector('.replies');
        handleCommentAddition(commentForm, commentTemplate, commentAdderUrl, commentEditionUrl, opinionAdderUrl, commentDeletionUrl, userProfilePath, mainThreadCommentId);
        repliesDiv.appendChild(commentForm);

    });
}