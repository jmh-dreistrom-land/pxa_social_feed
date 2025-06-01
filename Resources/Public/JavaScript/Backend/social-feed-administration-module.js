import DocumentService from '@typo3/core/document-service.js';
import Notification from '@typo3/backend/notification.js';
import Modal from '@typo3/backend/modal.js';
import $ from 'jquery';
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
    const confirmationButton = this.getDomElementByIdentifier('confirmationButton');

    if (confirmationButton) {
      confirmationButton.addEventListener('click', function (e) {
        e.preventDefault();

        const sender = e.target;

        const title = sender.data('confirmation-title') || 'Delete';
        const message = sender.data('confirmation-message') || 'Are you sure you want to delete this record ?';
        const url = sender.attr('href');
        const modal = Modal.confirm(title, message, Severity.warning);

        modal.on('confirm.button.cancel', function () {
          Modal.dismiss(modal);
        });

        modal.on('confirm.button.ok', function () {
          Modal.dismiss(modal);
          window.location.href = url;
        });
      });
    }
  }

  /**
   * Show window with facebook login
   *
   * @private
   */
  facebookLoginWindow() {
    const facebookLoginButton = this.getDomElementByIdentifier('facebookLoginButton');

    if (facebookLoginButton) {
      facebookLoginButton.addEventListener('click', function (e) {
        e.preventDefault();

        var sender = e.target;
        var w = 800;
        var h = 800;

        var y = window.top.outerHeight / 2 + window.top.screenY - h / 2;
        var x = window.top.outerWidth / 2 + window.top.screenX - w / 2;

        window.open(sender.attr('href'), 'Facebook login', 'height=' + h + ',width=' + w + 'top=' + y + ', left=' + x);
      });
    }
  }

  /**
   * Switch to different social type
   *
   * @private
   */
  changeSocialType() {
    /*
    this.getDomElementByIdentifier('selectSocialType').on('change', function () {
      var selectSocialType = $(this).find(':selected').val();

      window.location.href = $(getDomElementByIdentifier('socialTypeUrlKeep') + selectSocialType).val();
    });
     */
  }

  /**
   * Copy redirect uri to clipboard
   * @private
   */
  getRedirectUriButtonClick() {
    //new clipboard(this.getDomElementByIdentifier('copyRedirectUriButton'));
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
        fieldElement.value = e.data.value;
      }

      const storageTitleElement = this.getDomElementByIdentifier('feedsStorageTitle');
      if (storageTitleElement) {
        storageTitleElement.innerHTML = e.data.label;
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
   * Get selector
   * @param elementIdentifier
   * @return {string|null}
   * @private
   */
  getDomElementByIdentifier(elementIdentifier) {
    return document.querySelector(this.domElementsSelectors[elementIdentifier]);
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
