import Modal from '@typo3/backend/modal.js';
import Severity from '@typo3/backend/severity.js';
import { MessageUtility } from '@typo3/backend/utility/message-utility.js';

class SocialFeedAdministrationModule {
  constructor() {
    this.domElementsSelectors = {
      confirmationButton: '.delete-action,.confirmation-action',
      selectSocialType: '#select-type',
      socialTypeUrlKeep: '#type-url-',
      winStorageBrowser: '[data-identifier="browse-feeds-storage"]',
      feedsStorageInput: '[data-identifier="feeds-storage-input"]',
      feedsStorageTitle: '[data-identifier="feed-storage-title"]',
      copyRedirectUriButton: '.copy-redirect-uri-button',
      facebookLoginButton: '.facebook-login-link',
    };
  }

  initialize(settings) {
    this.settings = JSON.parse(settings);

    this.deleteConfirmation();
    this.facebookLoginWindow();
    this.changeSocialType();
    this.winStorageBrowser();
    this.getRedirectUriButtonClick();
  }

  /**
   * If user try to delete something
   *
   * @private
   */
  deleteConfirmation() {
    const confirmationButtons = this.getDomElementByIdentifier('confirmationButton', true);

    for (const button of confirmationButtons) {
      button.addEventListener('click', function (e) {
        e.preventDefault();

        const sender = e.currentTarget;

        const title = sender.dataset.confirmationTitle ?? 'Delete';
        const message = sender.dataset.confirmationMessage ?? 'Are you sure you want to delete this record ?';
        const url = sender.getAttribute('href');

        Modal.confirm(title, message, Severity.warning, [
          {
            text: TYPO3.lang['cancel'] || 'Cancel',
            active: true,
            trigger: function() {
              Modal.dismiss();
            }
          },
          {
            text: TYPO3.lang['yes'] || 'Yes',
            btnClass: 'btn btn-warning',
            trigger: function() {
              window.location.href = url;
              Modal.dismiss();
            }
          }
        ]);
      });
    }
  }

  /**
   * Show window with facebook login
   *
   * @private
   */
  facebookLoginWindow() {
    const facebookLoginButtons = this.getDomElementByIdentifier('facebookLoginButton', true);

    for (const button of facebookLoginButtons) {
      button.addEventListener('click', function (e) {
        e.preventDefault();

        var sender = e.currentTarget;
        var w = 800;
        var h = 800;

        var y = window.top.outerHeight / 2 + window.top.screenY - h / 2;
        var x = window.top.outerWidth / 2 + window.top.screenX - w / 2;

        window.open(sender.getAttribute('href'), 'Facebook login', 'height=' + h + ',width=' + w + 'top=' + y + ', left=' + x);
      });
    }
  }

  /**
   * Switch to different social type
   *
   * @private
   */
  changeSocialType() {
    const socialTypeElement = this.getDomElementByIdentifier('selectSocialType');

    if (socialTypeElement) {
      socialTypeElement.addEventListener('change', function (event) {
        const selectSocialType = event.target.options[event.target.selectedIndex].value;

        window.location.href = document.querySelector(this.getElementSelectorByIdentifier('socialTypeUrlKeep') + selectSocialType).value;
      }.bind(this));
    }
  }

  /**
   * Copy redirect uri to clipboard
   * @private
   */
  getRedirectUriButtonClick() {
    try {
      new ClipboardJS(this.getElementSelectorByIdentifier('copyRedirectUriButton'));
    } catch (error) {}
  }

  /**
   * Load browser pages window
   *
   * @private
   */
  winStorageBrowser() {
    window.addEventListener('message', function (e) {
      if (!MessageUtility.verifyOrigin(e.origin)) {
        throw 'Denied message sent by ' + e.origin;
      }

      if (typeof e.data.fieldName === 'undefined') {
        throw 'fieldName not defined in message';
      }

      if (typeof e.data.value === 'undefined') {
        throw 'value not defined in message';
      }

      const fieldElement = this.getInsertTarget(e.data.fieldName);
      if (fieldElement) {
        const pageId = e.data.value.match(/[^\d]*(\d+)/);
        fieldElement.value = pageId[1] ?? '';
      }

      const storageTitleElement = this.getDomElementByIdentifier('feedsStorageTitle');
      if (storageTitleElement) {
        storageTitleElement.innerText = e.data.label;
      }
    }.bind(this));

    const winStorageBrowser = this.getDomElementByIdentifier('winStorageBrowser');

    if (winStorageBrowser) {
      winStorageBrowser.addEventListener('click', function () {
        const insertTarget = this.getDomElementByIdentifier('feedsStorageInput');
        const randomIdentifier = Math.floor(Math.random() * 100000 + 1);

        if (insertTarget) {
          insertTarget.setAttribute('data-insert-target', randomIdentifier);
          this.openTypo3WinBrowserowser('db', randomIdentifier + '|||pages');
        }
      }.bind(this));
    }
  }

  /**
   * @private
   *
   * opens a popup window with the element browser
   *
   * @param mode
   * @param params
   */
  openTypo3WinBrowserowser(mode, params) {
    const url = this.getSetting('browserUrl') + '&mode=' + mode + '&bparams=' + params;
    Modal.advanced({
      type: Modal.types.iframe,
      content: url,
      size: Modal.sizes.large,
    });
  }

  /**
   * Get Element by Selector
   * @param elementIdentifier
   * @param all
   * @return {string|null}
   * @private
   */
  getDomElementByIdentifier(elementIdentifier, all=false) {
    if (all) {
      return document.querySelectorAll(this.getElementSelectorByIdentifier(elementIdentifier));
    }

    return document.querySelector(this.getElementSelectorByIdentifier(elementIdentifier));
  }

  getElementSelectorByIdentifier(elementIdentifier) {
    return this.domElementsSelectors[elementIdentifier];
  }

  /**
   * Get insert target
   * @param reference
   * @return {HTMLElement|null}
   * @private
   */
  getInsertTarget(reference) {
    return document.querySelector('[data-insert-target="' + reference + '"]');
  }

  /**
   * Get settings
   * @param key
   * @return {*|undefined}
   * @private
   */
  getSetting(key) {
    return this.settings[key] || null;
  }
}

export default new SocialFeedAdministrationModule();
