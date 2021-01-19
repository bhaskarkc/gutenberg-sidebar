/**
 * Internal block libraries
 */
const { __ } = wp.i18n;

const { Fragment } = wp.element;

const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;

const { registerPlugin } = wp.plugins;

const Component = () => (
	<Fragment>
		<PluginSidebarMoreMenuItem target="advert-settings-sidebar">
			{__("Advertisement Settings")}
		</PluginSidebarMoreMenuItem>
		<PluginSidebar
			name="advert-settings-sidebar"
			title={__("Gutenberg Boilerplate")}
		>
			<h2>{__("Hello World!")}</h2>
		</PluginSidebar>
	</Fragment>
);

registerPlugin("advert-settings", {
	icon: "admin-site",
	render: Component,
});
