"use strict";(self.webpackChunk=self.webpackChunk||[]).push([["rune-laenen-two-factor-auth"],{585:(e,t,r)=>{var i,s,o,n=r(3206),a=r(8254),l=r(4690);class c extends window.PluginBaseClass{init(){this._httpClient=new a.Z,this._submitButton=n.Z.querySelector(this.el,this.options.buttonSelector),this._codeInput=n.Z.querySelector(this.el,this.options.codeInputSelector),this._secretInput=n.Z.querySelector(this.el,this.options.secretInputSelector),this._csrfInput=n.Z.querySelector(this.el,this.options.csrfInputSelector),this._errorMessageWrapper=n.Z.querySelector(this.el,this.options.errorMessageWrapperSelector),this._errorMessage=n.Z.querySelector(this.el,this.options.errorMessageSelector),this.initListeners()}initListeners(){this._submitButton.addEventListener("click",this.onSubmitButtonClick.bind(this))}onSubmitButtonClick(){l.Z.create(this.el),this._errorMessageWrapper.classList.add(this.options.invisibleClass),this._httpClient.post(this.options.verificationUrl,JSON.stringify({code:this._codeInput.value,secret:this._secretInput.value,_csrf_token:this._csrfInput.value}),(e=>{const t=JSON.parse(e);"OK"!==t.status?(l.Z.remove(this.el),this.showErrorMessage(t.error?t.error:"Something went wrong!")):window.location.reload()}))}showErrorMessage(e){this._errorMessageWrapper.classList.remove(this.options.invisibleClass),this._errorMessage.innerHTML=e}}i=c,o={buttonSelector:".account-profile-2fa-setup-verify",codeInputSelector:"[name=otpVerification]",secretInputSelector:"[name=otpSecret]",csrfInputSelector:"[name=_csrf_token]",errorMessageWrapperSelector:".rl2fa-setup-verification-message",errorMessageSelector:".rl2fa-setup-verification-message .alert-content",invisibleClass:"d-none",verificationUrl:""},(s=function(e){var t=function(e,t){if("object"!=typeof e||null===e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var i=r.call(e,t||"default");if("object"!=typeof i)return i;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"==typeof t?t:String(t)}(s="options"))in i?Object.defineProperty(i,s,{value:o,enumerable:!0,configurable:!0,writable:!0}):i[s]=o,window.PluginManager.register("Rl2faVerificationPlugin",c,"[data-rl2fa-verification-plugin]")}},e=>{e.O(0,["vendor-shared"],(()=>{return t=585,e(e.s=t);var t}));e.O()}]);