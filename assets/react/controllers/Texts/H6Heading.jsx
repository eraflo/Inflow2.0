import React from "react";

export default class H6Heading extends React.Component {
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
            <h6 className="h6-heading" style={color}>
                {text}
            </h6>
        );
    }
}