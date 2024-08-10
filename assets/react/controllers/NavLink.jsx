import React from "react";
import UILabelSemiBold from "./Texts/UILabelSemiBold";

export default class NavLink extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            location: window.location.pathname,
            color: props.color
        };
    }

    render() {
        const location = this.state.location;
        const link = this.props.link;
        const text = this.props.text;
        const color = {color: this.state.color};

        let active = location === link ? 'active' : '';

        return (
            <a href={link} className={active}> <UILabelSemiBold text={text} color={color}/> </a>
        );
    }
}