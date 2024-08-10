import React from "react";

export default class H4Heading extends React.Component {
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
            <h4 className="h4-heading" style={color}>
                {text}
            </h4>
        );
    }
}