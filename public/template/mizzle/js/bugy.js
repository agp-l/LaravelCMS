const storedTheme = localStorage.getItem('theme')

const getPreferredTheme = () => {
  if (storedTheme) {
    return storedTheme
  }
  return window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'light'
}

const setTheme = function(theme) {
  if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    document.documentElement.setAttribute('data-bs-theme', 'dark')
  } else {
    document.documentElement.setAttribute('data-bs-theme', theme)
  }
}

setTheme(getPreferredTheme())

window.addEventListener('DOMContentLoaded', () => {
  var el = document.querySelector('.theme-icon-active');
  if (el != 'undefined' && el != null) {
    const showActiveTheme = theme => {
      const activeThemeIcon = document.querySelector('.theme-icon-active use')
      const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
      const svgOfActiveBtn = btnToActive.querySelector('.mode-switch use').getAttribute('href')

      document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
        element.classList.remove('active')
      })

      btnToActive.classList.add('active')
      activeThemeIcon.setAttribute('href', svgOfActiveBtn)
    }

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
      if (storedTheme !== 'light' || storedTheme !== 'dark') {
        setTheme(getPreferredTheme())
      }
    })

    showActiveTheme(getPreferredTheme())

    document.querySelectorAll('[data-bs-theme-value]')
      .forEach(toggle => {
        toggle.addEventListener('click', () => {
          const theme = toggle.getAttribute('data-bs-theme-value')
          localStorage.setItem('theme', theme)
          setTheme(theme)
          showActiveTheme(theme)
        })
      })

  }
})


//schování tlačítka 
document.addEventListener('DOMContentLoaded', function() {
  const buttons = document.querySelectorAll('.edit-button');

  buttons.forEach(button => {
    const container = button.closest('.container'); // Získání nadřazeného kontejneru, který bude mít mousemove event

    container.addEventListener('mousemove', function(event) {
      const rect = button.getBoundingClientRect();
      const x = event.clientX;
      const y = event.clientY;
      const distance = Math.sqrt(Math.pow(rect.left + rect.width / 2 - x, 2) + Math.pow(rect.top + rect.height / 2 - y, 2));

      if (distance < 50) {
        button.classList.add('visible-button');
        button.classList.remove('hidden-button');
      } else {
        button.classList.add('hidden-button');
        button.classList.remove('visible-button');
      }
    });
  });
});



document.querySelectorAll('.dropdown').forEach(function(dropdown) {
  dropdown.addEventListener('mouseleave', function() {
    this.querySelector('.dropdown-togglee').style.color = '#fff'; // Nastaví barvu textu při opuštění dropdown
  });

});






// document.querySelectorAll('.dropdown').forEach(function(dropdown) {
//   var dropdownToggle = dropdown.querySelector('.dropdown-toggle');

//   dropdown.addEventListener('mouseleave', function() {
//     dropdownToggle.style.color = '#fff'; // Nastaví barvu textu při opuštění dropdown
//     // Odebere třídu active, pokud je třeba
//     //   dropdown.classList.remove('active');
//   });

// });