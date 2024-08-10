import React from "react";

export default class P2Paragraph extends React.Component {
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
            <p className="p2-paragraph" style={color}>
                {text}
            </p>
        );
    }
}