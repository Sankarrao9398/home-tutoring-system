from flask import Flask, request, render_template, jsonify
from langchain_core.prompts import ChatPromptTemplate
from langchain_core.output_parsers import StrOutputParser
from langchain_community.llms import Ollama
import os
from dotenv import load_dotenv

app = Flask(__name__)
load_dotenv()

# Initialize LangChain components
prompt = ChatPromptTemplate.from_messages(
    [
        ("system", "You are a helpful assistant. Please respond to the user queries"),
        ("user", "Question:{question}")
    ]
)
llm = Ollama(model="llama2")
output_parser = StrOutputParser()
chain = prompt | llm | output_parser

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/ask', methods=['POST'])
def ask():
    question = request.form['question']
    if question:
        response = chain.invoke({"question": question})
        return jsonify({"response": response})
    else:
        return jsonify({"error": "No question provided"}), 400

if __name__ == '__main__':
    app.run(debug=True)
