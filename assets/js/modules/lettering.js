function Lettering(textContainer) {

    // Get the text content from the element
    const text = textContainer.textContent;
    
    // Create a new HTML string with each letter wrapped in a <span>
    const spannedText = Array.from(text).map((letter, index) => `<span class="char${index + 1}">${letter}</span>`).join('');
    
    // Replace the original text content with the new HTML string
    textContainer.innerHTML = spannedText;
}

export default Lettering;