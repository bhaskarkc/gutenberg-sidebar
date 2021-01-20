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
const { RadioControl, PanelRow } = wp.components;

/**
 * Meta robots input component.
 *
 * @since 1.0.0
 */
class AdvertType extends React.Component {
	constructor() {
		super();
	}

	render() {
		return (
			<div className="metatags-browser-title-field">
				<PanelRow>
					<RadioControl
						className="advert-content-type"
						label="Commercial content type"
						selected={
							this.props.metaFieldValue ? this.props.metaFieldValue : "none"
						}
						onChange={this.props.setMetaFieldValue}
						options={[
							{ label: "None", value: "none" },
							{ label: "Sponsored Content", value: "sponsored-content" },
							{ label: "Partnered Content", value: "partnered-content" },
							{ label: "Brought to you by", value: "brought-to-you-by" },
						]}
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
					meta: { metatags_robots_field: value },
				});
			},
		};
	}),
	withSelect((select) => {
		return {
			metaFieldValue: select("core/editor").getEditedPostAttribute("meta")[
				"metatags_robots_field"
			],
		};
	}),
])(AdvertType);
