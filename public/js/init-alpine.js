function data() {
    function getThemeFromLocalStorage() {
      // if user already changed the theme, use it
      if (window.localStorage.getItem('dark')) {
        return JSON.parse(window.localStorage.getItem('dark'))
      }
  
      // else return their preferences
      return (
        !!window.matchMedia &&
        window.matchMedia('(prefers-color-scheme: dark)').matches
      )
    }
  
    function setThemeToLocalStorage(value) {
      window.localStorage.setItem('dark', value)
    }
  
    return {
      dark: getThemeFromLocalStorage(),
      toggleTheme() {
        this.dark = !this.dark
        setThemeToLocalStorage(this.dark)
      },
      isSideMenuOpen: false,
      toggleSideMenu() {
        this.isSideMenuOpen = !this.isSideMenuOpen
      },
      closeSideMenu() {
        this.isSideMenuOpen = false
      },
      isNotificationsMenuOpen: false,
      toggleNotificationsMenu() {
        this.isNotificationsMenuOpen = !this.isNotificationsMenuOpen
      },
      closeNotificationsMenu() {
        this.isNotificationsMenuOpen = false
      },
      isProfileMenuOpen: false,
      toggleProfileMenu() {
        this.isProfileMenuOpen = !this.isProfileMenuOpen
      },
      closeProfileMenu() {
        this.isProfileMenuOpen = false
      },
      isPagesMenuOpen: false,
      togglePagesMenu() {
        this.isPagesMenuOpen = !this.isPagesMenuOpen
      },
      isPagesMenuOpen2: false,
      togglePagesMenu2(){
        this.isPagesMenuOpen2 = !this.isPagesMenuOpen2
      },
      isPagesMenuOpen3: false,
      togglePagesMenu3(){
        this.isPagesMenuOpen3 = !this.isPagesMenuOpen3
      },
      isPagesMenuOpen4: false,
      togglePagesMenu4(){
        this.isPagesMenuOpen4 = !this.isPagesMenuOpen4
      },
      isPagesMenuOpen5: false,
      togglePagesMenu5(){
        this.isPagesMenuOpen5 = !this.isPagesMenuOpen5
      },
      isPagesMenuOpen6: false,
      togglePagesMenu6(){
        this.isPagesMenuOpen6 = !this.isPagesMenuOpen6
      },
      isPagesMenuOpen7: false,
      togglePagesMenu7(){
        this.isPagesMenuOpen7 = !this.isPagesMenuOpen7
      }, 
      
      // Modal
      isModalOpen: false,
      trapCleanup: null,
      openModal() {
        this.isModalOpen = true
        this.trapCleanup = focusTrap(document.querySelector('#modal'))
      },
      closeModal() {
        this.isModalOpen = false
        this.trapCleanup()
      },
    }
  }
  