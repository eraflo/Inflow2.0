import React from "react";

export default class P1Paragraph extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            text: props.text,
            color: props.color,
            isGradient: props.isGradient
        };
    }

    render() {
        const text = this.state.text;
        const color = {color: this.state.color};
        if (this.state.isGradient) {
            color.background = `linear-gradient(90deg, ${this.state.color} 0%, #ffffff 100%)`;
            color.WebkitBackgroundClip = "text";
            color.WebkitTextFillColor = "transparent";
        }

        return (
            <p className="p1-paragraph" style={color}>
                {text}
            </p>
        );
    }
}