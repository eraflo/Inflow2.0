import React from "react";

export default class H3Heading extends React.Component {
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
            <h3 className="h3-heading" style={color}>
                {text}
            </h3>
        );
    }
}