/**
 * Internal block libraries
 */
const { __ } = wp.i18n;

const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
const { Component, Fragment, useState } = wp.element;
const { PanelBody, TextControl, CheckboxControl } = wp.components;
const { withSelect } = wp.data;
const { compose } = wp.compose;
const { registerPlugin } = wp.plugins;

class AdvertisementSettings extends Component {
	constructor() {
		super(...arguments);

		this.state = {
			key: "_advert_settings_fields",
			value: {
				_advert_enabled: "",
				_advert_type: "",
				_advert_name: "",
			},
		};

		wp.apiFetch({
			path: `/wp/v2/posts/${this.props.postId}`,
			method: "GET",
		}).then(
			(data) => {
				console.log(data.meta);
				// this.setState({ value: data.meta._hello_gutenberg_field });
				return data;
			},
			(err) => {
				return err;
			}
		);
	}

	// https://reactjs.org/docs/react-component.html#the-component-lifecycle
	static getDerivedStateFromProps(nextProps, state) {
		if (
			(nextProps.isPublishing || nextProps.isSaving) &&
			!nextProps.isAutoSaving
		) {
			wp.apiRequest({
				path: `/advert-settings/v1/update-meta?id=${nextProps.postId}`,
				method: "POST",
				data: state,
			}).then(
				(data) => {
					return data;
				},
				(err) => {
					return err;
				}
			);
		}
	}

	// https://reactgo.com/react-setstate-update-object/
	handleAdvName(val) {
		return this.setState({
			value: {
				...this.state.value,
				_advert_name: val,
			},
		});
	}

	handleAdvContentType(option) {
		return this.setState({
			value: {
				...this.state.value,
				_advert_type: option,
			},
		});
	}

	handleAdvEnable(selected) {
		return this.setState({
			value: {
				...this.state.value,
				_advert_enabled: selected,
			},
		});
	}

	render() {
		return (
			<Fragment>
				<PluginSidebarMoreMenuItem target="advert-settings-sidebar">
					{__("Advertisement Settings")}
				</PluginSidebarMoreMenuItem>
				<PluginSidebar name="advert-settings-sidebar" title={__("Settings")}>
					<PanelBody>
						<CheckboxControl
							heading="Advertisement"
							label="Advertisement Enabled?"
							help="Is the advertisement is enabled?"
							checked={this.state.value._advert_enabled}
							onChange={this.handleAdvEnable}
						/>
						<RadioControl
							label="Commercial content type"
							selected={this.state.value._advert_type}
							onchange={this.handleAdvContentType}
							options={[
								{ label: "None", value: "none" },
								{ label: "Sponsored Content", value: "sponsored-content" },
								{ label: "Partnered Content", value: "partnered-content" },
								{ label: "Brought to you by", value: "brought-to-you-by" },
							]}
						/>
						<TextControl
							label={__("Advertisement Name")}
							value={this.state.value._advert_name}
							onChange={this.handleAdvName}
						/>
					</PanelBody>
				</PluginSidebar>
			</Fragment>
		);
	}
}

const renderer = withSelect((select, { forceIsSaving }) => {
	const {
		getCurrentPostId,
		isSavingPost,
		isPublishingPost,
		isAutosavingPost,
	} = select("core/editor");
	return {
		postId: getCurrentPostId(),
		isSaving: forceIsSaving || isSavingPost(),
		isAutoSaving: isAutosavingPost(),
		isPublishing: isPublishingPost(),
	};
})(AdvertisementSettings);

registerPlugin("advert-settings", {
	icon: "admin-site",
	render: renderer,
});
