var Search = React.createClass({
    render: function() {
        Number.prototype.padLeft = function(base,chr){
            var  len = (String(base || 10).length - String(this).length)+1;
            return len > 0? new Array(len).join(chr || '0')+this : this;
        };
        var preparedDate = new Date(this.props.data.created_at);
        var dformat = [ (preparedDate.getMonth()+1).padLeft(),
                preparedDate.getDate().padLeft(),
                preparedDate.getFullYear()].join('/')+
            ' ' +
            [ preparedDate.getHours().padLeft(),
                preparedDate.getMinutes().padLeft(),
                preparedDate.getSeconds().padLeft()].join(':');
        var link = '/site/search/' + this.props.data.id;
        return (
            <tr className="search" >
                <td>{this.props.data.url}</td>
                <td>{dformat}</td>
                <td>{this.props.data.type}</td>
                <td>{this.props.data.status}</td>
                <td>{this.props.data.resources_count}</td>
                <td>
                    <a href={link}>show</a>
                </td>
            </tr>
        );
    }
});

var SearchBox = React.createClass({
    loadSearchesFromServer: function() {
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            cache: false,
            success: function(data) {
                this.setState({data: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },

    handleCommentSubmit: function(search) {
        var search = this.state.data;
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            type: 'POST',
            data: search,
            success: function(data) {
                this.loadSearchesFromServer();
            }.bind(this),
            error: function(xhr, status, err) {
                this.setState({data: comments});
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },

    getInitialState: function() {
        return {data: []};
    },

    componentDidMount: function() {
        this.loadSearchesFromServer();
        setInterval(this.loadSearchesFromServer, this.props.pollInterval);
    },

    render: function() {
        return (
            <div className="searchBox">
                <h1>Search Panel</h1>
                <SearchForm onCommentSubmit={this.handleCommentSubmit} />
                <SearchList data={this.state.data} />
            </div>
        );
    }
});

var SearchList = React.createClass({
    render: function() {
        var commentNodes = this.props.data.map(function(search) {
            return (
                <Search data={search} key={search.id}></Search>
            );
        });
        return (
            <table className="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Site</th><th>Date</th><th>Search Type</th><th>Status</th><th>Resources Count</th><th></th>
                </tr>
                </thead>
                <tbody className="SearchList">
                {commentNodes}
                </tbody>
            </table>
        );
    }
});

var SearchForm = React.createClass({
    validUrl: function (str) {
        return str.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/);
    },

    getInitialState: function() {
        return {url: '', text: '', type: 'img', isModalOpen: false};
    },

    enableSubmitButton: function(arg) {
        if (arg){
            document.getElementById('formSubmit').disabled = false
        } else {
            document.getElementById('formSubmit').disabled = true
        }
    },

    handleUrlChange: function(e) {
        console.log({url: e.target.value});
        console.log(this.validUrl(e.target.value));
        if (this.validUrl(e.target.value)) {
            this.setState({url: e.target.value});
            this.enableSubmitButton(true);
        } else {
            this.setState({url: ''});
            this.enableSubmitButton(false);
        }
    },

    handleTypeChange: function(e) {
        this.setState({type: e.target.value});
        if (e.target.value == 'text') {
            this.enableSubmitButton(false);
        } else if (this.validUrl(this.state.url.trim())) {
            this.enableSubmitButton(true);
        }}
    ,

    handleTextChange: function(e) {
        if (e.target.value.length < 3) {
            this.enableSubmitButton(false);
        } else {
            this.enableSubmitButton(true);
        }
        this.setState({text: e.target.value});
    },

    handleSubmit: function(e) {
        e.preventDefault();
        var url = this.state.url.trim();
        var text = this.state.text.trim();
        var type = this.state.type.trim();

        if (type == 'text' && text.length < 3) {
            return;
        }

        if (!type || !url) {
            return;
        }
        this.props.onCommentSubmit({url: url, type:type, text: text});
        this.setState({url: '', text: ''});
    },

    render: function() {
        return (
            <div>
                <form className="SearchForm" onSubmit={this.handleSubmit}>
                    <div className="form-group">
                        <input
                            className="form-control"
                            type="text"
                            placeholder="Site search"
                            onChange={this.handleUrlChange}
                        />
                    </div>
                    <div className="form-group">
                        <label className="radio-inline">
                            <input type="radio" name="type" value="img" defaultChecked onChange={this.handleTypeChange}/>Img
                        </label>
                        <label className="radio-inline">
                            <input type="radio" name="type" value="link" onChange={this.handleTypeChange}/>Link
                        </label>
                        <label className="radio-inline">
                            <input type="radio" name="type" value="text" onChange={this.handleTypeChange} />Text
                        </label>
                    </div>

                    {this.state.type == 'text' ?
                        <div className="form-group">
                            <input
                                className="form-control"
                                type="text"
                                placeholder="Say something..."
                                value={this.state.text}
                                onChange={this.handleTextChange}
                            />
                        </div>
                        : null}
                    <div className="form-group">
                        <input className="btn btn-primary" id="formSubmit" type="submit" value="Find" disabled/>
                    </div>
                </form>
            </div>
        );
    }
});

ReactDOM.render(
    <SearchBox url="/api/search?sort=-created_at" pollInterval={2000} />,
    document.getElementById('content')
);