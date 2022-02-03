/**
 * Local dependencies.
 */
import Sidebar from "./components/sidebar.js";

/**
 * WordPress dependencies.
 */
const { registerPlugin } = wp.plugins;

// import "./scss/index.scss";

/**
 * Register the MetaTags plugin.
 */
registerPlugin("advert-settings", {
    icon: "smiley",
    render: Sidebar,
});
