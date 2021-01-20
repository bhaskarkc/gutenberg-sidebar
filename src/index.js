/**
 * Local dependencies.
 */
import Sidebar from "./components/sidebar.js";

/**
 * WordPress dependencies.
 */
const { registerPlugin } = wp.plugins;

/**
 * Register the MetaTags plugin.
 */
registerPlugin("advert-settings", {
	icon: "editor-customchar",
	render: Sidebar,
});
