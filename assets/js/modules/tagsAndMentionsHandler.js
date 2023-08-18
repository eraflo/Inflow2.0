//NOT YET FINISHED

let articlesContent = document.querySelectorAll('.articleContent p');
let commentsContent = document.querySelectorAll('.comment-content');

for (articleContent of articlesContent) {
    //addMentionLink(articleContent);
    addTagLink(articleContent);
}

for (commentContent of commentsContent) {
    //addMentionLink(commentContent);
    addTagLink(commentContent);
}

/* function addMentionLink(content) {
    let mentionRegex = /(^|\s)(@[a-zA-Z0-9_]+)/g;
    content.innerHTML = content.innerHTML.replace(mentionRegex, '$1<a href="/users/">$2</a>');
} */

function addTagLink(content) {
    let mentionRegex = /(^|\s)(#[a-zA-Z0-9_]+)/g;
    content.innerHTML = content.innerHTML.replace(mentionRegex, '$1<a href="/search?tag=$2">$2</a>');
}