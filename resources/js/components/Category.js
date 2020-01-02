import React, { Component } from 'react';
import ReactDOM from 'react-dom';
export default class Category extends Component {
    constructor(props) {
        super(props);
        this.state = {
            category: []
        }
        this.setState((props) => {
            // Important: read `state` instead of `this.state` when updating.
            return { category: props }
        });
    }
    renderCate() {
        if (category) {

        } else {
            return this.state.category.map(category => {
                return (
                    <tr>
                        <td>{ category.name }</td>
                        <td>{ category.email }</td>
                    </tr>
                );
            })
        }
    }
    render() {
        return (
            <div className="table-responsive">      
                <table className="table">
                    <tr>
                        <td>#</td>
                        <td>Name</td>
                        <td>DESC</td>
                    </tr>
                    { this.renderUsers() }
                </table>
            </div>
        );
    }
}

if (document.getElementById('category')) {
    ReactDOM.render(<Category data={data}/>,document.getElementById('category').getAttribute('data'));
}
