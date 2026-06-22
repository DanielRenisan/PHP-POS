// Simulated API response (replace this with actual API call)
const apiResponse = {
  // skinColor: '#87CEEB',
};

// Access the root element
const root = document.documentElement;

// Update custom properties with API-provided colors or defaults
root.style.setProperty(
  "--primary-border-color",
  apiResponse.skinColor ||
    getComputedStyle(document.documentElement).getPropertyValue(
      "--primary-border-color"
    )
);
root.style.setProperty(
  "--primary-icon-color",
  apiResponse.skinColor ||
    getComputedStyle(document.documentElement).getPropertyValue(
      "--primary-icon-color"
    )
);

const fullscreenToggle = document.getElementById("fullscreenToggle");

function toggleFullscreen() {
  if (!document.fullscreenElement) {
    if (document.documentElement.requestFullscreen) {
      document.documentElement.requestFullscreen();
    } else if (document.documentElement.mozRequestFullScreen) {
      /* Firefox */
      document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
      /* Chrome, Safari and Opera */
      document.documentElement.webkitRequestFullscreen();
    } else if (document.documentElement.msRequestFullscreen) {
      /* IE/Edge */
      document.documentElement.msRequestFullscreen();
    }
    // Store fullscreen state in sessionStorage
    sessionStorage.setItem("fullscreen", "true");
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen();
    } else if (document.mozCancelFullScreen) {
      /* Firefox */
      document.mozCancelFullScreen();
    } else if (document.webkitExitFullscreen) {
      /* Chrome, Safari and Opera */
      document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) {
      /* IE/Edge */
      document.msExitFullscreen();
    }
    // Remove fullscreen state from sessionStorage
    sessionStorage.removeItem("fullscreen");
  }
}

function checkFullscreenOnLoad() {
  // Check sessionStorage for fullscreen state on page load
  const isFullscreen = sessionStorage.getItem("fullscreen");
  if (isFullscreen === "true") {
    toggleFullscreen();
  }
}

fullscreenToggle.addEventListener("click", toggleFullscreen);
document.addEventListener("DOMContentLoaded", checkFullscreenOnLoad);

// Handle changing URLs without full reload
const links = document.querySelectorAll("a[data-navigate]");
links.forEach((link) => {
  link.addEventListener("click", function (event) {
    event.preventDefault();
    const url = this.getAttribute("data-navigate");
    history.pushState(null, null, url);
  });
});

window.addEventListener("popstate", function () {
  checkFullscreenOnLoad();
});