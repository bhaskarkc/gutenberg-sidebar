/**
 * External dependencies.
 */
import React from "react";

/**
 * WordPress dependencies.
 */
const { __ } = wp.i18n;
const { compose } = wp.compose;
const { withDispatch, withSelect } = wp.data;
const { TextControl, PanelRow } = wp.components;

/**
 * Meta description input component.
 *
 * @since 1.0.0
 */
class AdvertName extends React.Component {
	constructor() {
		super();
	}

	render() {
		return (
			<div className="metatags-browser-title-field">
				<PanelRow>
					<TextControl
						className="advert-name"
						label={__("Advertisement Name")}
						value={this.props.metaFieldValue || ""}
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
					meta: { metatags_description_field: value },
				});
			},
		};
	}),
	withSelect((select) => {
		return {
			metaFieldValue: select("core/editor").getEditedPostAttribute("meta")[
				"metatags_description_field"
			],
		};
	}),
])(AdvertName);
