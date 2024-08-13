import React from "react";
import UILabelSemiBold from "../Texts/UILabelSemiBold";

export default class TransparentButton extends React.Component {
        
    constructor(props) {
        super(props);
        this.state = {
            text : props.text, 
            link : props.link,
            padding : props.padding,
            margin : props.margin
        };
    }


    render() {

        const text = this.state.text;
        const link = this.state.link;
        const padding = this.state.padding;
        const margin = this.state.margin;

        let style = {
            padding: padding,
            margin: margin
        };

        return (
            <div className="transparent-button" style={style}>
                <a href={link}><UILabelSemiBold text={text} /></a>
            </div>
        );
    }
}