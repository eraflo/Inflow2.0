window.addEventListener('load', function () {
  console.log('The entire page has fully loaded.');
  let playlistLinks = document.querySelectorAll("a.cardLink");
  let tracksContainer = document.querySelector(".tracksContainer");
  let loadingAnimDiv = tracksContainer.querySelector("div.loading-overlay");
  let tracks = tracksContainer.querySelector("#tracks");

  //console.log(playlistLinks);

  for (let playlistLink of playlistLinks) {
    playlistLink.addEventListener("click", (e) => {
      loadingAnimDiv.style.display = "flex";
      tracksContainer.querySelector("h1").style.display = "none";
      for (let track of tracks.querySelectorAll("iframe")) {
        track.remove();
      }
    });
  }

  document.addEventListener("turbo:frame-load", (event) => {
    loadingAnimDiv.style.display = "none";
  });
  
  document.addEventListener("turbo:request-end", (event) => {
    loadingAnimDiv.style.display = "none";
  });
  
  document.addEventListener("turbo:request-error", (event) => {
    loadingAnimDiv.style.display = "none";
  });
});
