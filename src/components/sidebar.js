/**
 * External dependencies.
 */
import React from 'react';

/**
 * Local dependencies.
 */
import AdvertSettingsEnabled from './advert-enabled.js';
import AdvertName from './advert-name.js';
import AdvertType from './advert-type.js';

/**
 * WordPress dependencies.
 */
const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { PluginSidebarMoreMenuItem, PluginSidebar } = wp.editPost;

/**
 * Sidebar component voor the gutenberg editor.
 *
 * @since 1.0.0
 */
class Sidebar extends React.Component {
	render() {
		return (
			<Fragment>
				<PluginSidebarMoreMenuItem
					target="metatags-sidebar"
					icon="editor-customchar"
				>
					{ __( 'MetaTags', 'metatags' ) }
				</PluginSidebarMoreMenuItem>

				<PluginSidebar
					name="metatags-sidebar"
					title={ __( 'MetaTags', 'metatags' ) }
				>
					<div className="metabox-sidebar-content">
						<AdvertSettingsEnabled />
						<AdvertName />
						<AdvertType />
					</div>
				</PluginSidebar>
			</Fragment>
		);
	}
}

export default Sidebar;
