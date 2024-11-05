# WordPress plugin to use ChatGPT functionalities
This plugin is used for ChatGPT support on WordPress.<br>
The plugin is using a custom backend API to send requests.<br>

All requests are sent to the backend API, and GPT responses are sent from the API to Wordpress.

## Requirements
This plugin require to also use Classic Editor of WordPress.

## Usage
- Setup required fields (like backend API URL).
- Create multiple prompts on the backend API service.
- Go to the classic editor of WordPress, select text, and click the classic editor's button which feature a cat _(test case)_. The selected text will be sent to GPT for correction.
- Go to classic editor, and go below the editor to find the "Kumo GPT" part, where you can choose the prompt to use.

Prompts can be used with some parameters. Everything is configured on the backend API. 

---

Todo list :
- Add message on settings saved
- Add a button to refresh prompts _(coming from backend API)_
