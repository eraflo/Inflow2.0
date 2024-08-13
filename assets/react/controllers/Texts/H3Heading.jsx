import React from "react";

export default class H3Heading extends React.Component {
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
            <h3 className="h3-heading" style={color}>
                {text}
            </h3>
        );
    }
}