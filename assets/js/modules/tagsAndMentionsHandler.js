//NOT YET FINISHED
/* let commentsContent = document.querySelectorAll('.comment-content');

for (commentContent of commentsContent) {
    //addMentionLink(commentContent);
    addTagLink(commentContent);
} */

export function addMentionLinks(content, mentions) {
    for (let mention of mentions) {
        let mentionRegex = new RegExp('(@' + mention.username + ')', 'g');
        content = content.replace(mentionRegex, '<a href="/users/' + mention.id + '">@' + mention.username + '</a>');
    }
    return content;
}

export function addTagLinks(content) {
    let mentionRegex = /(#[a-zA-Z0-9_]+)/g;
    //console.log('"'+$1+'"');
    return content.replace(mentionRegex, '<a href="/search?tag=$1">$1</a>');
}