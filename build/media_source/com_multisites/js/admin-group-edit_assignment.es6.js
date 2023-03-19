/**
 * @copyright  (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
(() => {
  'use strict';

  const onChange = (value) => {
    if (value === '-' || parseInt(value, 10) === 0) {
      document.getElementById('extensionselect-group').classList.add('hidden');
    } else {
      document.getElementById('extensionselect-group').classList.remove('hidden');
    }
  };

  const onBoot = () => {
    const element = document.getElementById('jform_extensionassignment');

    if (element) {
      // Initialise the state
      onChange(element.value);

      // Check for changes in the state
      element.addEventListener('change', ({ target }) => { onChange(target.value); });
    }

    document.removeEventListener('DOMContentLoaded', onBoot);
  };
  document.addEventListener('DOMContentLoaded', onBoot);
})();
