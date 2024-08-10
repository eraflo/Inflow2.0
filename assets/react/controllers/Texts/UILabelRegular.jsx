import React from "react";

export default class UILabelRegular extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            text: props.text,
            color: props.color
        };
    }

    render() {
        const text = this.state.text;
        const color = {color: this.state.color};

        return (
            <div className="ui-label-regular" style={color}>
                {text}
            </div>
        );
    }
}