// Get the default theme of user
function setThemeOnLoad() {
    if (localStorage.theme === "dark" || 
        (!("theme" in localStorage) && 
            window.matchMedia("(prefers-color-scheme: dark)").matches)
    ) {
        document.documentElement.classList.add("dark");
    }else{
        document.documentElement.classList.remove("dark");
    }
}

// Switch theme
function switchTheme() {
    if (localStorage.theme === "dark" || 
        (!("theme" in localStorage) && 
            window.matchMedia("(prefers-color-scheme: dark)").matches)
    ) {
        document.documentElement.classList.remove("dark");
        localStorage.theme = "light";
    }else{
        document.documentElement.classList.add("dark");
        localStorage.theme = "dark";
    }
}

export {setThemeOnLoad, switchTheme};