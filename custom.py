from flask import Flask, request, jsonify, render_template
import os
import pdfplumber
from langchain_community.embeddings import OllamaEmbeddings
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.chains.combine_documents import create_stuff_documents_chain
from langchain_core.prompts import ChatPromptTemplate
from langchain.chains import create_retrieval_chain
from langchain_community.vectorstores import FAISS
from dotenv import load_dotenv
import time
from langchain_ollama import OllamaLLM

# Load environment variables
load_dotenv()

app = Flask(__name__)

# Initialize Ollama LLM
llm = OllamaLLM(model="llama2")

# Chat Prompt Template
prompt = ChatPromptTemplate.from_template(
    """
    Answer the questions based on the provided context only.
    Please provide the most accurate response based on the question.
    <context>
    {context}
    </context>
    Questions: {input}
    """
)

# Vector embedding and vector store database (stored in memory)
vectors = None

# Function to read PDF and text files
def read_file(file):
    if file.filename.endswith(".pdf"):
        with pdfplumber.open(file) as pdf:
            text = ""
            for page in pdf.pages:
                text += page.extract_text() or ""
        return text.strip()
    elif file.filename.endswith(".txt"):
        return file.read().decode("utf-8").strip()
    else:
        return None

# Route for the main HTML page
@app.route('/')
def index():
    return render_template("index1.html")

# Endpoint for document embedding
@app.route('/embed-documents', methods=['POST'])
def embed_documents():
    global vectors
    file = request.files.get("file")
    if not file or file.filename == '':
        return jsonify({"message": "No file uploaded"}), 400

    data = read_file(file)
    if data is None:
        return jsonify({"message": "Unsupported file format. Please upload PDF or text files."}), 400

    try:
        embeddings = OllamaEmbeddings()
        text_splitter = RecursiveCharacterTextSplitter(chunk_size=1000, chunk_overlap=200)
        documents = text_splitter.create_documents([data[:50]])
        vectors = FAISS.from_documents(documents, embeddings)
        return jsonify({"message": "Document embedding is complete."})
    except Exception as e:
        return jsonify({"message": f"Error during embedding: {str(e)}"}), 500

# Endpoint for querying
@app.route('/query', methods=['POST'])
def query():
    global vectors
    input_prompt = request.form.get("input_prompt")

    if vectors is None:
        return jsonify({"message": "No embedded documents found. Please embed documents first."}), 400

    try:
        document_chain = create_stuff_documents_chain(llm, prompt)
        retriever = vectors.as_retriever()
        retrieval_chain = create_retrieval_chain(retriever, document_chain)

        start_time = time.process_time()
        response = retrieval_chain.invoke({'input': input_prompt})
        response_time = time.process_time() - start_time

        return jsonify({
            "answer": response['answer'],
            "response_time": f"{response_time:.2f} seconds",
        })
    except Exception as e:
        return jsonify({"message": f"Error during query: {str(e)}"}), 500

if __name__ == '__main__':
    app.run(debug=True)
