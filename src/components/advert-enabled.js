/**
 * External dependencies.
 */
import React from "react";

/**
 * WordPress dependencies.
 */
const { compose } = wp.compose;
const { withDispatch, withSelect } = wp.data;
const { CheckboxControl, PanelRow } = wp.components;

/**
 * Browser title input component.
 *
 * @since 1.0.0
 */
class AdvertSettingsEnabled extends React.Component {
	constructor() {
		super();
	}

	render() {
		return (
			<div className="metatags-browser-title-field">
				<PanelRow>
					<CheckboxControl
						className="advert-enabled"
						label="Advertisement Enabled?"
						value={this.props.metaFieldValue || false}
						onChange={this.props.setMetaFieldValue}
					/>
				</PanelRow>
			</div>
		);
	}
}

export default compose([
	withDispatch((dispatch) => {
		return {
			setMetaFieldValue: function (value) {
				dispatch("core/editor").editPost({
					meta: { _advert_enabled: value },
				});
			},
		};
	}),
	withSelect((select) => {
		console.log(select("core/editor").getEditedPostAttribute("meta"));
		return {
			metaFieldValue: select("core/editor").getEditedPostAttribute("meta")
				._advert_enabled,
		};
	}),
])(AdvertSettingsEnabled);
