(function(Drupal) {
  'use strict';

  Drupal.behaviors.navigation_button = {
    attach: function(context, settings) {
      const mobile_navigation = once('navigation_button', '.js-navigation-button', context);
      mobile_navigation.forEach(toggleMobileNavigation);
    }
  };

  /**
   * Allow to display the main navigation in mobile.
   */
  function toggleMobileNavigation(element) {
    const navigation = document.querySelector('.js-navigation');
    
    element.addEventListener('click', () => {
      navigation.classList.toggle('navigation-open');
    });
  }


})(Drupal);
