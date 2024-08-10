import React from "react";
import UILabelSemiBold from "../Texts/UILabelSemiBold";

export default class TransparentButton extends React.Component {
        
    constructor(props) {
        super(props);
        this.state = {text : props.text, link : props.link};
    }


    render() {

        const text = this.state.text;
        const link = this.state.link

        return (
            <div className="transparent-button">
                <a href={link}><UILabelSemiBold text={text} /></a>
            </div>
        );
    }
}