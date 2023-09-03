let opinionManager = require('./modules/opinionAdder.js');
let commentManager = require('./modules/commentManager.js');
let loadingAnimation = require('./modules/loadingAnimation.js');
let tagsAndMentionsHandler = require('./modules/tagsAndMentionsHandler.js');

document.addEventListener('DOMContentLoaded', () => {

    let articleId = document.querySelector('.articleShow').getAttribute('data-article-id');

    let commentAdderUrl = '/articles/' + articleId + '/comments/add';
    let commentEditionUrl = '/articles/' + articleId + '/comments/{comment_id}/edit';
    let opinionAdderUrl = '/articles/' + articleId + '/comments/{comment_id}/opinions/add';
    let commentDeletionUrl = '/articles/' + articleId + '/comments/{comment_id}/delete';
    let userProfilePath = '/users/{user_id}';
    let getRepliesUrl = '/articles/' + articleId + '/comments/{comment_id}/replies';

    let commentsDiv = document.querySelector('div.comments');

    let commentTemplate = document.querySelector('.comment.template').cloneNode(true);
    commentTemplate.classList.remove('template');

    // Handling comment creation

    let commentForm = document.querySelector('form#commentAddition');
    commentManager.handleCommentAddition(
        commentForm,
        commentTemplate,
        '/articles/' + articleId + '/comments/add',
        '/articles/' + articleId + '/comments/{comment_id}/edit',
        '/articles/' + articleId + '/comments/{comment_id}/opinions/add',
        '/articles/' + articleId + '/comments/{comment_id}/delete',
        '/users/{user_id}'
    );



    

    // add events to the existing article-related comments

    let comments = document.querySelectorAll('div.comment');

    // keep mainThreadCommentId through loops to get the last non-reply comment
    let mainThreadCommentId;

    for (let comment of comments) {

        if (comment.classList.contains('template')) {
            continue;
        }

        let commentId = comment.getAttribute('data-comment-id');

        // handling opinion adding

        let commentOpinionAdderForm = comment.querySelector('.opinions form');
        opinionManager.addOpinion('/articles/' + articleId + '/comments/' + commentId + '/opinions/add', commentOpinionAdderForm);

        // adding events to edition link

        commentForm = commentForm.cloneNode(true);
        commentForm.setAttribute('id', 'commentEdition');

        let commentEditionLink = comment.querySelector("a.commentEdition");
        commentManager.addEditionClickEvent(
            commentEditionLink,
            commentForm,
            '/articles/' + articleId + '/comments/' + commentId + '/edit',
            '/users/{user_id}'
        );

        // handling comment deletion

        let commentDeletionForm = comment.querySelector(".comment form[name='commentDeletion']");
        commentManager.addFormDeletionEvent(commentDeletionForm, '/articles/' + articleId + '/comments/' + commentId + '/delete');




        // handling replies

        commentForm = commentForm.cloneNode(true);
        commentForm.setAttribute('id', 'replyAddition');

        if (!comment.classList.contains('reply')) {

            // Main Thread Comments

            mainThreadCommentId = comment.getAttribute('data-comment-id');
        } else {
            mainThreadCommentId = comment.closest('.replies').closest('.comment').getAttribute('data-comment-id');
        }

        //let replies = comment.closest('div.comment-with-replies').querySelector('.replies');
        //console.log(mainThreadCommentId);

        // handling adding replies to comments

        /*
        NOTE: all replies are related to the non-reply comment,
        this means that if a reply-comment replies to another reply-comment,
        the reply-comment that the reply-comment replies to is only identified by its author in the reply-comment content
        */

        commentManager.addReplyAdditionClickEvent(
            mainThreadCommentId,
            comment.querySelector('a.add-reply'),
            commentForm,
            '/articles/' + articleId + '/comments/add',
            '/articles/' + articleId + '/comments/' + commentId + '/edit',
            '/articles/' + articleId + '/comments/' + commentId + '/opinions/add',
            '/articles/' + articleId + '/comments/' + commentId + '/delete',
            '/users/{user_id}',
            commentTemplate
        );

        // handling showing replies to a comment

        let linksWithReplies = comment.nextElementSibling;
        let showRepliesLink = linksWithReplies.querySelector('a.show-replies');
        let hideRepliesLink = linksWithReplies.querySelector('a.hide-replies');
        let repliesDiv = linksWithReplies.querySelector('div.replies');

        commentManager.getRepliesClickEvent(showRepliesLink, '/articles/' + articleId + '/comments/' + mainThreadCommentId + '/replies');
        
        showReplies(repliesDiv, showRepliesLink, hideRepliesLink, commentTemplate, commentForm, mainThreadCommentId, commentAdderUrl, commentEditionUrl,  opinionAdderUrl, commentDeletionUrl, userProfilePath);
        showRepliesLink.addEventListener('repliesRetrieval', (e) => {
            //  using e.target to avoid the "closure over the loop" issue
            tagsAndMentionsHandler.addTagLink(e.target.closest('.comment').querySelector('.comment-content').innerHTML);
        });
        
        hideRepliesLink.addEventListener('click', (e) => {
            repliesDiv.style.display = 'none';
            //  TEMPORARY: clearing all previous replies
            //  TO DO: add cache system and a reload button
            repliesDiv.innerHTML = "";
            hideRepliesLink.style.display = 'none';
            showRepliesLink.style.display = 'flex';
        });

        //comment.querySelector('.comment-content').innerHTML = tagsAndMentionsHandler.addTagLink(comment.querySelector('.comment-content').innerHTML);

    }
    
    /* commentsDiv.addEventListener('showRepliesRequested', (e) => {
        loadingAnimation.add(e.target.closest('.replies'));
    });
    commentsDiv.addEventListener('repliesShown', (e) => {
        loadingAnimation.remove(e.target.closest('.replies'));
    }); */

    // DO NOT DELETE THE showReplies() FUNCTION: it makes it possible to avoid the "closure over the loop" issue due to shallow copies
    // https://stackoverflow.com/questions/750486/javascript-closure-inside-loops-simple-practical-example

    function showReplies(repliesDiv, showRepliesLink, hideRepliesLink, commentTemplate, commentForm, mainThreadCommentId, commentAdderUrl, commentEditionUrl,  opinionAdderUrl, commentDeletionUrl, userProfilePath) {
        showRepliesLink.addEventListener('repliesRetrieval', (e) => {
            //console.log(mainThreadCommentId);
            showRepliesLink.style.display = 'none';
            repliesDiv.style.display = 'flex';
            hideRepliesLink.style.display = 'flex';
            //  clearing responses that have been added to avoid duplicate replies
            repliesDiv.innerHTML = "";
            for (let reply of e.detail.replies) {
                let replyElement = commentManager.initializeComment(reply, commentTemplate, commentForm, mainThreadCommentId, commentAdderUrl, commentEditionUrl,  opinionAdderUrl, commentDeletionUrl, userProfilePath);
                replyElement.classList.add('reply');
                repliesDiv.appendChild(replyElement);
            }
        });
    }

});